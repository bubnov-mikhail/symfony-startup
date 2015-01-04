<?php

namespace App\UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * Find users by roles
     *
     * @param array $roles
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findByRoles(array $roles)
    {
        $_users = $this->findAll();

        $users = [];
        if (is_array($_users)) {
            foreach ($_users as $i => $user) {
                $add = false;
                foreach ($roles as $role) {
                    if ($user->hasRole($role)) {
                        $add = true;
                        break;
                    }
                }
                if ($add) {
                    $users[] = $user;
                }
            }
        }

        return $users;
    }

    /**
     * Find users by not this roles
     *
     * @param array findByNotThisRoles
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findByNotThisRoles(array $notThisRoles)
    {
        $_users = $this->findAll();

        $users = [];
        if (is_array($_users)) {
            foreach ($_users as $i => $user) {
                $add = true;
                foreach ($notThisRoles as $role) {
                    if ($user->hasRole($role)) {
                        $add = false;
                        break;
                    }
                }
                if ($add) {
                    $users[] = $user;
                }
            }
        }

        return $users;
    }
}
