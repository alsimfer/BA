<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="log") 
 */
class Log
{
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $userLastName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $userFirstName;
	
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $field;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $action;

    /**
     * @ORM\Column(type="integer")
     */
    private $objectId;    

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $dateTime;  

    /**
     * @ORM\Column(type="string", length=5000)
     */
    private $log;

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
     * Set userLastName
     *
     * @param string $userLastName
     *
     * @return Log
     */
    public function setUserLastName($userLastName)
    {
        $this->userLastName = $userLastName;

        return $this;
    }

    /**
     * Get userLastName
     *
     * @return string
     */
    public function getUserLastName()
    {
        return $this->userLastName;
    }

    /**
     * Set userFirstName
     *
     * @param string $userFirstName
     *
     * @return Log
     */
    public function setUserFirstName($userFirstName)
    {
        $this->userFirstName = $userFirstName;

        return $this;
    }

    /**
     * Get userFirstName
     *
     * @return string
     */
    public function getUserFirstName()
    {
        return $this->userFirstName;
    }

    /**
     * Set field
     *
     * @param string $field
     *
     * @return Log
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get field
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set action
     *
     * @param string $action
     *
     * @return Log
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set objectId
     *
     * @param integer $objectId
     *
     * @return Log
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * Get objectId
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     *
     * @return Log
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set log
     *
     * @param string $log
     *
     * @return Log
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Log
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
