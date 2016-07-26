<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="patient") 
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Diese E-Mail wird bereits verwendet",
 *     groups={"registration"}
 * )
 */
class Patient
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Der Vorname darf nicht länger als {{ limit }} Zeichen sein."
     * )     
     */
    private $firstName = '';
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Der Nachname darf nicht länger als {{ limit }} Zeichen sein."
     * )
     */
    private $lastName = '';

    /**
     * @ORM\Column(type="date", length=50, nullable=true)
     * @Assert\Date()
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)     
     */
    private $sex = '';

    /**
     * @ORM\ManyToOne(targetEntity="Hospital", inversedBy="patients") 
     * @ORM\JoinColumn(name="hospital_id", referencedColumnName="id") 
     */
    private $hospital;

    /**
     * @ORM\ManyToOne(targetEntity="Caretaker", inversedBy="patients") 
     * @ORM\JoinColumn(name="caretaker_id", referencedColumnName="id") 
     */
    private $caretaker;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)     
     */
    private $address = '';


    /**
     * @ORM\Column(type="string", unique=true, length=150, nullable=true)
     * @Assert\Email(
     *     message = "Dieser Wert '{{ value }}' ist keine gültige E-Mail.",
     *     checkMX = true
     * )
     * @Assert\Length(
     *     max = 150,
     *     maxMessage = "Die E-Mail darf nicht länger als {{ limit }} Zeichen sein."
     * )    
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\Length(
     *      min = 4,
     *      max = 20,
     *      minMessage = "Die Nummer darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Die Nummer darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $phoneNumber = '';

    /**
    * @ORM\OneToMany(targetEntity="MedCheckup", mappedBy="patient") 
    */
    private $medCheckups;

    /**
    * @ORM\OneToMany(targetEntity="PatientArrangementReference", mappedBy="patient") 
    */
    private $patArrRefs;

    public function __construct() 
    {
        $this->medCheckups = new ArrayCollection();
        $this->patArrRefs = new ArrayCollection();
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Patient
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
     * @return Patient
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
     * @return Patient
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
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Patient
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
     * Add medCheckup
     *
     * @param \AppBundle\Entity\MedCheckup $medCheckup
     *
     * @return Patient
     */
    public function addMedCheckup(\AppBundle\Entity\MedCheckup $medCheckup)
    {
        $this->medCheckups[] = $medCheckup;

        return $this;
    }

    /**
     * Remove medCheckup
     *
     * @param \AppBundle\Entity\MedCheckup $medCheckup
     */
    public function removeMedCheckup(\AppBundle\Entity\MedCheckup $medCheckup)
    {
        $this->medCheckups->removeElement($medCheckup);
    }

    /**
     * Get medCheckups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedCheckups()
    {
        return $this->medCheckups;
    }

    /**
     * Add arrangement
     *
     * @param \AppBundle\Entity\PatientArrangementReference $arrangement
     *
     * @return Patient
     */
    public function addArrangement(\AppBundle\Entity\PatientArrangementReference $arrangement)
    {
        $this->arrangements[] = $arrangement;

        return $this;
    }

    /**
     * Remove arrangement
     *
     * @param \AppBundle\Entity\PatientArrangementReference $arrangement
     */
    public function removeArrangement(\AppBundle\Entity\PatientArrangementReference $arrangement)
    {
        $this->arrangements->removeElement($arrangement);
    }

    /**
     * Get arrangements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArrangements()
    {
        return $this->arrangements;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Patient
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set sex
     *
     * @param string $sex
     *
     * @return Patient
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Patient
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set hospital
     *
     * @param \AppBundle\Entity\Hospital $hospital
     *
     * @return Patient
     */
    public function setHospital(\AppBundle\Entity\Hospital $hospital = null)
    {
        $this->hospital = $hospital;

        return $this;
    }

    /**
     * Get hospital
     *
     * @return \AppBundle\Entity\Hospital
     */
    public function getHospital()
    {
        return $this->hospital;
    }

    /**
     * Set caretaker
     *
     * @param \AppBundle\Entity\Caretaker $caretaker
     *
     * @return Patient
     */
    public function setCaretaker(\AppBundle\Entity\Caretaker $caretaker = null)
    {
        $this->caretaker = $caretaker;

        return $this;
    }

    /**
     * Get caretaker
     *
     * @return \AppBundle\Entity\Caretaker
     */
    public function getCaretaker()
    {
        return $this->caretaker;
    }

    /**
     * Add patArrRef
     *
     * @param \AppBundle\Entity\PatientArrangementReference $patArrRef
     *
     * @return Patient
     */
    public function addPatArrRef(\AppBundle\Entity\PatientArrangementReference $patArrRef)
    {
        $this->patArrRefs[] = $patArrRef;

        return $this;
    }

    /**
     * Remove patArrRef
     *
     * @param \AppBundle\Entity\PatientArrangementReference $patArrRef
     */
    public function removePatArrRef(\AppBundle\Entity\PatientArrangementReference $patArrRef)
    {
        $this->patArrRefs->removeElement($patArrRef);
    }

    /**
     * Get patArrRefs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatArrRefs()
    {
        return $this->patArrRefs;
    }
}
