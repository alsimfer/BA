<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sys_user") 
 */
class SysUser
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="UserGroup", inversedBy="sysUsers") 
    * @ORM\JoinColumn(name="user_group_id", referencedColumnName="id") 
    */
    private $userGroup;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $firstName;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", unique=true, length=150)
     */
    private $email;

    /**
     * @ORM\Column(type="string", unique=true, length=20)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string")
     */
    private $password;


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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Sys_User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Sys_User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Sys_User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Sys_User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return SysUser
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set userGroupsId
     *
     * @param \AppBundle\Entity\UserGroup $userGroupsId
     *
     * @return SysUser
     */
    public function setUserGroupsId(\AppBundle\Entity\UserGroup $userGroupsId = null)
    {
        $this->userGroupsId = $userGroupsId;

        return $this;
    }

    /**
     * Get userGroupsId
     *
     * @return \AppBundle\Entity\UserGroup
     */
    public function getUserGroupsId()
    {
        return $this->userGroupsId;
    }

    /**
     * Set userGroup
     *
     * @param \AppBundle\Entity\UserGroup $userGroup
     *
     * @return SysUser
     */
    public function setUserGroup(\AppBundle\Entity\UserGroup $userGroup = null)
    {
        $this->userGroup = $userGroup;

        return $this;
    }

    /**
     * Get userGroup
     *
     * @return \AppBundle\Entity\UserGroup
     */
    public function getUserGroup()
    {
        return $this->userGroup;
    }
}
