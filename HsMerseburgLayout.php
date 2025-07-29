<?php

use ElanEv\StudipLayoutHsMerseburg\PatchTemplateFactory;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use SpeechToTextPlugin\Models\Job;

/**
 * @SuppressWarnings(StaticAccess)
 */
class HsMerseburgLayout extends StudIPPlugin implements SystemPlugin
{
    public function __construct(
        ContainerInterface $container,
        protected LoggerInterface $logger,
    ) {
        parent::__construct();

        require_once __DIR__ . '/vendor/autoload.php';

        $this->registerObservers();
        $this->customizeFavicons();

        if (!$GLOBALS['perm']->have_perm("root")) {
            if (Navigation::hasItem('/avatar')) {
                $avatarNavigation = Navigation::getItem('/avatar');
                foreach (array_keys($avatarNavigation->getSubNavigation()) as $key) {
                    if ($key !== 'logout') {
                        $avatarNavigation->removeSubNavigation($key);
                    }
                }
            }

            $templatesPath = realpath($this->getPluginPath() . "/resources/templates");
            $previous = $container->get(\Flexi\Factory::class);
            $container->set(
                \Flexi\Factory::class,
                new PatchTemplateFactory($previous, $templatesPath)
            );
            $GLOBALS['template_factory'] = $container->get(\Flexi\Factory::class);
            $this->mixVite();
            $this->loadStartPage();
        }
    }

    // as soon as this plugin gets enabled, activate this plugin for
    // "nobody" users
    public static function onEnable($id)
    {
        RolePersistence::assignPluginRoles($id, [7]);
    }

    private function registerObservers(): void
    {
        \NotificationCenter::on(
            Job::class . 'DidSucceed',
            $this->jobDidUpdate(...)
        );
    }

    private function jobDidUpdate(string $event, Job $job)
    {
        $this->logger->info(
            sprintf("%s: %s (%s)", static::class, $event, 'Job succeeded.')
        );
        $this->sendSuccessMail($job);
        $this->deleteInputFileRef($job);
    }

    private function sendSuccessMail(Job $job): void
    {
        $mailText = $this->prepareMailBody($job);

        $mail = new StudipMail();
        $mail->addRecipient($job->user->email)
            ->setSubject(sprintf('Audiotranskription abgeschlossen: "%s"', $job->input_file_ref->name))
            ->setBodyText($mailText);

        $outputFileRefs = $job->getOutputFileRefs();
        foreach ($outputFileRefs as $outputFileRef) {
            $mail->addStudipAttachment($outputFileRef);
        }

        $mail->send();
    }

    private function deleteInputFileRef(Job $job): void
    {
        $inputFileRef = $job->input_file_ref;

        $job->input_file_ref_id = null;
        $job->store();

        $inputFileRef->delete();
    }

    private function loadStartPage(): void
    {
        // TODO: check if plugin exists and is activated
        if (strpos($_SERVER['REQUEST_URI'], "dispatch.php/start") !== false) {
            header('Location: ' . URLHelper::getURL('plugins.php/speechtotextplugin'));
            sess()->save();
            exit;
        }
    }

    private function mixVite(): void
    {
        $vite = new ElanEv\Vite\Manifest(
            manifestPath: __DIR__ . '/dist/.vite/manifest.json',
            basePath: $this->getPluginUrl() . '/dist/',
            dev: false
        );
        $tags = $vite->createTags('resources/js/app.js');

        foreach ($tags->preload as $tag) {
            PageLayout::addHeadElement(...$tag);
        }
        foreach ($tags->css as $tag) {
            PageLayout::addHeadElement(...$tag);
        }
        foreach ($tags->js as [, $attributes]) {
            PageLayout::addScript($attributes['src'], array_diff_key($attributes, ['src' => true]));
        }

        PageLayout::addStylesheet($vite->getURL('style.css'));
    }

    private function prepareMailBody(Job $job): string
    {
        $user = $job->user;

        $body = <<<'EOD'
            Guten Tag %s,
            Ihre Audiotranskription ist abgeschlossen: "%s".
            Das Transkript finden Sie im Anhang der E-Mail.

            Viele Grüße
            Ihr Team von SL²

            --
            Dies ist eine automatische Benachrichtigung, des Audiotranskriptionsdienstes der Hochschule Merseburg.
            EOD;

        return sprintf($body, trim($user->getFullName()), $job->input_file_ref_name);
    }

    private function customizeFavicons(): void
    {
        $favicons = $this->getPluginUrl() . '/favicons/';
        PageLayout::removeHeadElement('link', ['rel' => 'apple-touch-icon']);
        PageLayout::removeHeadElement('link', ['rel' => 'icon']);
        PageLayout::removeHeadElement('meta', ['name' => 'TileImage']);

        PageLayout::addHeadElement('link', ['rel' => 'apple-touch-icon', 'sizes' => '180x180', 'href' => $favicons . 'apple-touch-icon.png']);
        PageLayout::addHeadElement('link', ['rel' => 'icon', 'type' => 'image/png', 'sizes' => '64x64', 'href' => $favicons . 'favicon-64x64.png']);
        PageLayout::addHeadElement('link', ['rel' => 'icon', 'type' => 'image/png', 'sizes' => '32x32', 'href' => $favicons . 'favicon-32x32.png']);
        PageLayout::addHeadElement('link', ['rel' => 'icon', 'type' => 'image/png', 'sizes' => '16x16', 'href' => $favicons . 'favicon-16x16.png']);
        PageLayout::addHeadElement('meta', ['name' => 'TileImage', 'content' => Assets::image_path('mstile-144x144.png')]);
    }
}
