<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="patient_arrangement_reference") 
 * @UniqueEntity(
 *     fields={"patient", "arrangement"}, 
 *     groups={"registration"},
 *     message="Diese Patient-Kurs Paar existiert bereits"
 * )
 */
class PatientArrangementReference
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="Patient", inversedBy="patArrRefs") 
    * @ORM\JoinColumn(name="patient_id", referencedColumnName="id") 
    */
    private $patient;

    /**
    * @ORM\ManyToOne(targetEntity="Arrangement", inversedBy="patArrRefs")
    * @ORM\JoinColumn(name="arrangement_id", referencedColumnName="id") 
    */
    private $arrangement;

    public function __construct() 
    {
        $this->patient = new ArrayCollection();
        $this->arrangement = new ArrayCollection();
    }

    /**
    * @ORM\Column(type="integer")
    */
    private $registered;


    /**
    * @ORM\Column(type="integer")
    */
    private $attended;


    /**
     * @ORM\Column(type="string", length=1023)
     * @Assert\Length(
     *      max = 1023,
     *      maxMessage = "Die Kommentare dürfen nicht länger als {{ limit }} Zeichen sein."
     * )
     */
    private $comments = '';
    


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
     * Set attended
     *
     * @param integer $attended
     *
     * @return PatientArrangementReference
     */
    public function setAttended($attended)
    {
        $this->attended = $attended;

        return $this;
    }

    /**
     * Get attended
     *
     * @return integer
     */
    public function getAttended()
    {
        return $this->attended;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return PatientArrangementReference
     */
    public function setComments($comments)
    {
        $this->comments = !isset($comments) ? '' : $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set patient
     *
     * @param \AppBundle\Entity\Patient $patient
     *
     * @return PatientArrangementReference
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
     * Set arrangement
     *
     * @param \AppBundle\Entity\Arrangement $arrangement
     *
     * @return PatientArrangementReference
     */
    public function setArrangement(\AppBundle\Entity\Arrangement $arrangement = null)
    {
        $this->arrangement = $arrangement;

        return $this;
    }

    /**
     * Get arrangement
     *
     * @return \AppBundle\Entity\Arrangement
     */
    public function getArrangement()
    {
        return $this->arrangement;
    }

    /**
     * Set registered
     *
     * @param integer $registered
     *
     * @return PatientArrangementReference
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;

        return $this;
    }

    /**
     * Get registered
     *
     * @return integer
     */
    public function getRegistered()
    {
        return $this->registered;
    }
}
