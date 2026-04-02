<?php

class RegisterWhisperxApiHealth extends \Migration
{
    private const BASE_DIR = 'public/plugins_packages/elan-ev/HsMerseburgLayout/';

    public function up()
    {
        $scheduler = \CronjobScheduler::getInstance();
        $taskId = $scheduler->registerTask(
            self::BASE_DIR . 'lib/StudipLayoutHsMerseburg/Cronjobs/WhisperxApiHealth.php',
            true,
        );
        if ($taskId) {
            $scheduler->schedule(
                $taskId,
                parameters: ['verbose' => true]
            )->activate();
        }
    }

    public function down()
    {
        $task = \CronjobTask::findOneByFilename(
            self::BASE_DIR . 'lib/StudipLayoutHsMerseburg/Cronjobs/WhisperxApiHealth.php',
        );

        $scheduler = \CronjobScheduler::getInstance();
        if ($task->task_id) {
            $scheduler->unregisterTask($task->task_id);
        }
    }
}
