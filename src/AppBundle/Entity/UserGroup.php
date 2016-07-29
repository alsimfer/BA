<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_group") 
 */
class UserGroup
{
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Der Name darf nicht länger als {{ limit }} Zeichen sein."
     * )     
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     * @Assert\Length(
     *      max = 500,
     *      maxMessage = "Die Beschreibung darf nicht länger als {{ limit }} Zeichen sein."
     * )
     */
    private $description;

	/**
	* @ORM\OneToMany(targetEntity="SysUser", mappedBy="userGroup") 
	*/
	private $sysUsers;

	public function __construct() 
	{
		$this->sysUsers = new ArrayCollection();
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
     * @param string $name
     *
     * @return UserGroup
     */
    public function setName($name)
    {
        $this->name = is_null($name) ? '' : $name;;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return UserGroup
     */
    public function setDescription($description)
    {
        $this->description = is_null($description) ? '' : $description;;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add sysUser
     *
     * @param \AppBundle\Entity\SysUser $sysUser
     *
     * @return UserGroup
     */
    public function addSysUser(\AppBundle\Entity\SysUser $sysUser)
    {
        $this->sysUsers[] = $sysUser;

        return $this;
    }

    /**
     * Remove sysUser
     *
     * @param \AppBundle\Entity\SysUser $sysUser
     */
    public function removeSysUser(\AppBundle\Entity\SysUser $sysUser)
    {
        $this->sysUsers->removeElement($sysUser);
    }

    /**
     * Get sysUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSysUsers()
    {
        return $this->sysUsers;
    }
}
