<?php

namespace App\UserBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class SlaveRoleChoiceList extends SimpleChoiceList
{
    const WORKER = 'ROLE_WORKER';
    const SLAVE = 'ROLE_SLAVE';

    protected static $titles = [
        self::WORKER => 'sitebeat.cabinet.roles.worker',
        self::SLAVE => 'sitebeat.cabinet.roles.slave',
    ];

    public function __construct()
    {
        $choices = [
            self::WORKER  => self::$titles[self::WORKER],
            self::SLAVE => self::$titles[self::SLAVE],
        ];
        parent::__construct($choices);
    }

    public static function getRoleTitle($role)
    {
        return self::$titles[$role];
    }
}
