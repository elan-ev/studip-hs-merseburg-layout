<?php

class RegisterDeleteUsers extends \Migration
{
    private const BASE_DIR = 'public/plugins_packages/elan-ev/HsMerseburgLayout/';

    public function up()
    {
        $scheduler = \CronjobScheduler::getInstance();
        $taskId = $scheduler->registerTask(
            self::BASE_DIR . 'lib/StudipLayoutHsMerseburg/Cronjobs/DeleteUser.php',
            true,
        );
        if ($taskId) {
            $scheduler->schedule(
                $taskId,
                hour: 0,
                minute: 0,
                parameters: ['dry_run' => false, 'verbose' => true, 'inactive_days' => 31]
            )->activate();

        }
    }

    public function down()
    {
        $task = \CronjobTask::findOneByFilename(
            self::BASE_DIR . 'lib/StudipLayoutHsMerseburg/Cronjobs/DeleteUser.php',
        );

        $scheduler = \CronjobScheduler::getInstance();
        if ($task->task_id) {
            $scheduler->unregisterTask($task->task_id);
        }
    }
}
