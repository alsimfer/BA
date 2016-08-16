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

    /**
    * @ORM\OneToMany(targetEntity="NavigationRules", mappedBy="userGroup") 
    */
    private $navigationRules;

	public function __construct() 
	{
		$this->sysUsers = new ArrayCollection();
        $this->navigationRules = new ArrayCollection();
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

    /**
     * Add navigationRule
     *
     * @param \AppBundle\Entity\NavigationRules $navigationRule
     *
     * @return UserGroup
     */
    public function addNavigationRule(\AppBundle\Entity\NavigationRules $navigationRule)
    {
        $this->navigationRules[] = $navigationRule;

        return $this;
    }

    /**
     * Remove navigationRule
     *
     * @param \AppBundle\Entity\NavigationRules $navigationRule
     */
    public function removeNavigationRule(\AppBundle\Entity\NavigationRules $navigationRule)
    {
        $this->navigationRules->removeElement($navigationRule);
    }

    /**
     * Get navigationRules
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNavigationRules()
    {
        return $this->navigationRules;
    }

    public function iterateVisible() {
        $return = array();
        
        foreach($this as $key => $value) {
            if ($value instanceof PersistentCollection || $value instanceof ArrayCollection) {
                continue;
            }

            if ($value instanceof \DateTime) {
                $return[$key] = (string)$value->format("d.m.Y H:i:s");
                continue;
            }
            
            $return[$key] = (string)$value;
        }

        return $return;
    }

    public function __toString() {
        try {
            return (string)$this->getName().' (id = '.(string)$this->getId().')';
        } catch (Exception $e) {
           return get_class($this).'@'.spl_object_hash($this); // If it is not possible, return a preset string to identify instance of object, e.g.
        }
        
    
    }
}
