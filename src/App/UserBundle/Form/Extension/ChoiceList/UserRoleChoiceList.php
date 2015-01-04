<?php

namespace App\UserBundle\Form\Extension\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

class UserRoleChoiceList extends SimpleChoiceList
{
    const USER = 'ROLE_USER';
    const ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    protected static $titles = [
        self::USER => 'Пользователь',
        self::ADMIN => 'Администратор',
        self::ROLE_SUPER_ADMIN => 'Супер администратор',
    ];

    public function __construct()
    {
        $choices = [
            self::USER  => self::$titles[self::USER],
            self::ADMIN => self::$titles[self::ADMIN],
            self::ROLE_SUPER_ADMIN => self::$titles[self::ROLE_SUPER_ADMIN],
        ];
        parent::__construct($choices);
    }

    public static function getRoleTitle($role)
    {
        return self::$titles[$role];
    }
}
