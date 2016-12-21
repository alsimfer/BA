<?php

/**
 * $ php bin/console doctrine:generate:entities AppBundle/Entity/
 * $ php bin/console doctrine:schema:update --force
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\PersistentCollection;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\CoachingRepository")
 * @ORM\Table(name="coaching") 
 */
class Coaching
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
    * @ORM\ManyToOne(targetEntity="Patient", inversedBy="coachings") 
    * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", onDelete="CASCADE") 
    * @Assert\NotBlank(
    *    message="Dieses Feld muss ausgefüllt werden."
    * )
    */
    private $patient;

    /**
    * @ORM\ManyToOne(targetEntity="SysUser", inversedBy="coachings") 
    * @ORM\JoinColumn(name="sys_user_id", referencedColumnName="id", onDelete="CASCADE") 
    * @Assert\NotBlank(
    *    message="Dieses Feld muss ausgefüllt werden."
    * )
    */
    private $sysUser;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\Length(
     *      max = 500,
     *      maxMessage = "Die Zielbeschreibung darf nicht laenger als {{ limit }} Zeichen sein."
     * )
     * @Assert\NotBlank(
     *    message="Dieses Feld muss ausgefüllt werden."
     * )
     */
    private $weekGoal;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     * @Assert\NotBlank(
     *    message="Dieses Feld muss ausgefüllt werden."
     * )
     */
    private $dateAndTime;

    

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
     * Set weekGoal
     *
     * @param string $weekGoal
     *
     * @return Coaching
     */
    public function setWeekGoal($weekGoal)
    {
        $this->weekGoal = $weekGoal;

        return $this;
    }

    /**
     * Get weekGoal
     *
     * @return string
     */
    public function getWeekGoal()
    {
        return $this->weekGoal;
    }

    /**
     * Set dateAndTime
     *
     * @param \DateTime $dateAndTime
     *
     * @return Coaching
     */
    public function setDateAndTime($dateAndTime)
    {
        $this->dateAndTime = $dateAndTime;

        return $this;
    }

    /**
     * Get dateAndTime
     *
     * @return \DateTime
     */
    public function getDateAndTime()
    {
        return $this->dateAndTime;
    }

    /**
     * Set patient
     *
     * @param \AppBundle\Entity\Patient $patient
     *
     * @return Coaching
     */
    public function setPatient(\AppBundle\Entity\Patient $patient = null)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return \AppBundle\Entity\Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }

    /**
     * Set sysUser
     *
     * @param \AppBundle\Entity\SysUser $sysUser
     *
     * @return Coaching
     */
    public function setSysUser(\AppBundle\Entity\SysUser $sysUser = null)
    {
        $this->sysUser = $sysUser;

        return $this;
    }

    /**
     * Get sysUser
     *
     * @return \AppBundle\Entity\SysUser
     */
    public function getSysUser()
    {
        return $this->sysUser;
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
            return (string)$this->getId();
        } catch (Exception $e) {
           return get_class($this).'@'.spl_object_hash($this); // If it is not possible, return a preset string to identify instance of object, e.g.
        }
        
    
    }
}
