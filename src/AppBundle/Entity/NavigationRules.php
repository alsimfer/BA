<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="navigation_rules") 
 */
class NavigationRules
{
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="UserGroup", inversedBy="navigationRules") 
    * @ORM\JoinColumn(name="user_group_id", referencedColumnName="id") 
    */
    private $userGroup;    
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $navLiId;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $path;
	
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $buttonName;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $urlsPermitted;

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
     * Set navLiId
     *
     * @param string $navLiId
     *
     * @return NavigationRules
     */
    public function setNavLiId($navLiId)
    {
        $this->navLiId = $navLiId;

        return $this;
    }

    /**
     * Get navLiId
     *
     * @return string
     */
    public function getNavLiId()
    {
        return $this->navLiId;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return NavigationRules
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set userGroup
     *
     * @param \AppBundle\Entity\UserGroup $userGroup
     *
     * @return NavigationRules
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

    /**
     * Set buttonName
     *
     * @param string $buttonName
     *
     * @return NavigationRules
     */
    public function setButtonName($buttonName)
    {
        $this->buttonName = $buttonName;

        return $this;
    }

    /**
     * Get buttonName
     *
     * @return string
     */
    public function getButtonName()
    {
        return $this->buttonName;
    }

    /**
     * Set actionsPermitted
     *
     * @param string $actionsPermitted
     *
     * @return NavigationRules
     */
    public function setActionsPermitted($actionsPermitted)
    {
        $this->actionsPermitted = $actionsPermitted;

        return $this;
    }

    /**
     * Get actionsPermitted
     *
     * @return string
     */
    public function getActionsPermitted()
    {
        return $this->actionsPermitted;
    }

    /**
     * Set urlsPermitted
     *
     * @param string $urlsPermitted
     *
     * @return NavigationRules
     */
    public function setUrlsPermitted($urlsPermitted)
    {
        $this->urlsPermitted = $urlsPermitted;

        return $this;
    }

    /**
     * Get urlsPermitted
     *
     * @return string
     */
    public function getUrlsPermitted()
    {
        return $this->urlsPermitted;
    }
}
