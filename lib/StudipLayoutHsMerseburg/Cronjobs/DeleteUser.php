<?php

namespace ElanEv\StudipLayoutHsMerseburg\Cronjobs;

class DeleteUser extends \CronJob
{
    public static function getName()
    {
        return _('Inaktive Nutzende löschen');
    }

    public static function getDescription()
    {
        return _('Lösche Nutzende nach 31 Tagen Inaktivität');
    }

    public static function getParameters()
    {
        return [
            'dry_run' => [
                'type'        => 'boolean',
                'default'     => false,
                'status'      => 'optional',
                'description' => _('Sollen die zu löschenden inaktiven Nutzenden nur ausgegeben statt tatsächlich gelöscht zu werden'),
            ],
            'inactive_days' => [
                'type'        => 'integer',
                'default'     => 31,
                'status'      => 'optional',
                'description' => _('Nach wie vielen Tagen sollen inaktive Nutzende gelöscht werden (Default: 31 Tage)?'),
            ],
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
        $inactiveUsers = $this->getInactiveUsers($parameters['inactive_days']);

        foreach ($inactiveUsers as $userId) {
            if ($parameters['verbose']) {
                printf("Deleting inactive user: %s\n", $userId);
            }
            if (!$parameters['dry_run']) {
                $management = new \UserManagement($userId);
                $management->deleteUser();
            }
        }
    }

    public function tearDown()
    {
    }

    public function getInactiveUsers(int $inactiveDays): iterable
    {
        $inactiveSince = (new \DateTime(sprintf('midnight -%d days', $inactiveDays)))->getTimestamp();

        $sql = "SELECT
                    u.user_id
                FROM
                    `auth_user_md5` u
                JOIN
                    `user_online` uo
                ON
                    uo.user_id = u.user_id
                WHERE
                    uo.last_lifesign < ?";

        return \DBManager::get()->fetchFirst($sql, [$inactiveSince]);
    }
}
