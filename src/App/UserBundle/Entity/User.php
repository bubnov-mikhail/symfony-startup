<?php

namespace App\UserBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * App\UserBundle\Entity\User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\UserBundle\Entity\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;
    
    private $myRole;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Magic to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        if (!$this->name) {
            return $this->username;
        }

        return $this->name;
    }

    /**
     * Set myRole
     *
     * @param  string $myRole
     * @return User
     */
    public function setMyRole($myRole)
    {
        $this->setRoles([$myRole]);

        return $this;
    }

    /**
     * Get myRole
     *
     * @return string
     */
    public function getMyRole()
    {
        $myRole = $this->getRoles();

        return $myRole[0];
    }

    /**
     * Check if user's role is in provided role list
     * @param array inRoles
     * @return boolean
     */
    public function isInRoles(array $inRoles)
    {
        $myRoles = $this->getRoles();
        foreach ($myRoles as $role) {
            if (in_array($role, $inRoles)) {
                return true;
            }
        }

        return false;
    }
    
    public function setPlainPassword($password)
    {
        if (false == empty($password)) {
            $this->plainPassword = $password;
        }
        
        return $this;
    }
}
