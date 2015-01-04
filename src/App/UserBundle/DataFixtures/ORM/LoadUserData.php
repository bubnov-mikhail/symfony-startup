<?php

namespace App\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $manipulator = $this->container->get('fos_user.user_manager');

        $user = $manipulator->createUser();
        $user->setUsername('customer');
        $user->setEmail('customer@email.com');
        $user->setPlainPassword('observer');
        $user->setEnabled(true);
        $user->addRole('ROLE_CUSTOMER');
        $user->setSuperAdmin(false);
        $manipulator->updateUser($user);
        $this->addReference('customer', $user);

        $admin = $manipulator->createUser();
        $admin->setUsername('admin');
        $admin->setEmail('admin@email.com');
        $admin->setPlainPassword('observer');
        $admin->setEnabled(true);
        $admin->addRole('ROLE_ADMIN');
        $admin->setSuperAdmin(true);
        $manipulator->updateUser($admin);
        $this->addReference('admin', $admin);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 1;
    }
}
