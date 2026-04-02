<?php

namespace ElanEv\StudipLayoutHsMerseburg\Cronjobs;

use Metrics;

class WhisperxApiHealth extends \CronJob
{
    public static function getName()
    {
        return _('`whisperx-api` prüfen');
    }

    public static function getDescription()
    {
        return _('Prüft die Erreichbarkeit des `whisperx-api`-Backends');
    }

    public static function getParameters()
    {
        return [
            'verbose' => [
                'type'        => 'boolean',
                'default'     => false,
                'status'      => 'optional',
                'description' => _('Sollen Ausgaben erzeugt werden'),
            ],
        ];
    }

    public function setUp()
    {
    }

    public function execute($last_result, $parameters = [])
    {
        $whisperxApiUrl = $_ENV['WHISPERX_API_URL'] ?? null;
        if (!$whisperxApiUrl) {
            if ($parameters['verbose']) {
                echo "URL of `whisperx-api` could not be found.\n";
            }
        }

        $client = new \GuzzleHttp\Client([
            'base_uri' => $whisperxApiUrl,
            'timeout' => 2.0,
        ]);

        $statusCode = 0;
        $count = 0;

        try {
            $timer = Metrics::startTimer();
            $response = $client->request('GET', '/jobs');
            $timer('plugins.hsmerseburglayout.jobs.timer');
            $statusCode = $response->getStatusCode();
            Metrics::gauge('plugins.hsmerseburglayout.jobs.status', $statusCode);
            if ($statusCode === 200) {
                $body = json_decode((string) $response->getBody());
                $count = is_array($body) ? count($body) : 0;
                Metrics::gauge('plugins.hsmerseburglayout.jobs.count', $count);
            }
            if ($parameters['verbose']) {
                printf("Requested `whisperx-api`: status=%d count=%d\n", $statusCode, $count);
            }
        } catch (\Exception $e) {
            Metrics::count('plugins.hsmerseburglayout.jobs.error', 1);
        }
    }

    public function tearDown()
    {
    }
}
