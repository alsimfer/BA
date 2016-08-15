<?php

/**
 * $ php bin/console doctrine:generate:entities AppBundle/Entity/
 * $ php bin/console doctrine:schema:update --force
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
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
    * @ORM\JoinColumn(name="patient_id", referencedColumnName="id") 
    * @Assert\NotBlank()
    */
    private $patient;

    /**
    * @ORM\ManyToOne(targetEntity="SysUser", inversedBy="coachings") 
    * @ORM\JoinColumn(name="sys_user_id", referencedColumnName="id") 
    * @Assert\NotBlank()
    */
    private $sysUser;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\Length(
     *      max = 500,
     *      maxMessage = "Die Zielbeschreibung darf nicht laenger als {{ limit }} Zeichen sein."
     * )
     * @Assert\NotBlank()
     */
    private $weekGoal;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     * @Assert\NotBlank()
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
}
