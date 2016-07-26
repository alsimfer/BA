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
 * @ORM\Table(name="med_checkup") 
 */
class MedCheckup
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
    * @ORM\ManyToOne(targetEntity="Patient", inversedBy="medCheckups") 
    * @ORM\JoinColumn(name="patient_id", referencedColumnName="id") 
    * @Assert\NotBlank()
    */
    private $patient;

    /**
    * @ORM\ManyToOne(targetEntity="SysUser", inversedBy="medCheckups") 
    * @ORM\JoinColumn(name="sys_user_id", referencedColumnName="id") 
    * @Assert\NotBlank()
    */
    private $sysUser;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Der Name darf nicht länger als {{ limit }} Zeichen sein."
     * )
     * @Assert\NotBlank()
     */
    private $type = '';

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     * @Assert\NotBlank()
     */
    private $dateAndTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height = '';

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weight = '';    

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $waist = '';   

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hips = '';   

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $source = '';


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
     * Set type
     *
     * @param string $type
     *
     * @return MedCheckup
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return MedCheckup
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set weight
     *
     * @param float $weight
     *
     * @return MedCheckup
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set waist
     *
     * @param integer $waist
     *
     * @return MedCheckup
     */
    public function setWaist($waist)
    {
        $this->waist = $waist;

        return $this;
    }

    /**
     * Get waist
     *
     * @return integer
     */
    public function getWaist()
    {
        return $this->waist;
    }

    /**
     * Set hips
     *
     * @param integer $hips
     *
     * @return MedCheckup
     */
    public function setHips($hips)
    {
        $this->hips = $hips;

        return $this;
    }

    /**
     * Get hips
     *
     * @return integer
     */
    public function getHips()
    {
        return $this->hips;
    }

    /**
     * Set patient
     *
     * @param \AppBundle\Entity\Patient $patient
     *
     * @return MedCheckup
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
     * @return MedCheckup
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

    /**
     * Set source
     *
     * @param string $source
     *
     * @return MedCheckup
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set dateAndTime
     *
     * @param \DateTime $dateAndTime
     *
     * @return MedCheckup
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
}
