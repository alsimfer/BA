<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="sys_user") 
 * @UniqueEntity(
 *     fields={"email"}, 
 *     groups={"createUserAction"},
 *     message="Diese E-Mail wird bereits verwendet"
 * )
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
    * @ORM\ManyToOne(targetEntity="Hospital", inversedBy="sysUsers") 
    * @ORM\JoinColumn(name="hospital_id", referencedColumnName="id") 
    */
    private $hospital;

    /**
    * @ORM\OneToMany(targetEntity="MedCheckup", mappedBy="sysUser") 
    */
    private $medCheckups;

    /**
    * @ORM\OneToMany(targetEntity="Coaching", mappedBy="sysUser") 
    */
    private $coachings;
    
    /**
    * @ORM\OneToMany(targetEntity="Patient", mappedBy="sysUser") 
    */
    private $patients;

    /**
    * @ORM\OneToMany(targetEntity="Arrangement", mappedBy="sysUser") 
    */
    private $arrangements;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Der Vorname darf nicht länger als {{ limit }} Zeichen sein."
     * )
     * @Assert\NotBlank()
     */
    private $firstName;
    
    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Der Nachname darf nicht länger als {{ limit }} Zeichen sein."
     * )
     * @Assert\NotBlank()
     */
    private $lastName;


    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})     
     */
    private $address;
    
    /**
     * @ORM\Column(type="date", length=50, nullable=true)
     * @Assert\Date()
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})     
     */
    private $sex;

    /**
     * @ORM\Column(type="string", unique=true, length=150)
     * @Assert\Email(
     *     message = "Der Wert '{{ value }}' ist keine gültige E-Mail.",
     *     checkMX = true,
     *     groups={"createUserAction"}
     * )
     * @Assert\Length(
     *     max = 150,
     *     maxMessage = "Die E-Mail darf nicht länger als {{ limit }} Zeichen sein.",
     *     groups={"createUserAction"}
     * )
     * @Assert\NotBlank(
     *     message="E-Mail muss vorhanden sein",
     *     groups={"createUserAction", "loginAction"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=20, options={"default" : ""})
     * @Assert\Length(
     *      min = 4,
     *      max = 15,
     *      minMessage = "Die Nummer darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Die Nummer darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string")
     */
    private $password;


   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medCheckups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->coachings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->patients = new \Doctrine\Common\Collections\ArrayCollection();
        $this->arrangements = new \Doctrine\Common\Collections\ArrayCollection();
        
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
     * @return SysUser
     */
    public function setFirstName($firstName)
    {
        $this->firstName = is_null($firstName) ? '' : $firstName;;

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
     * @return SysUser
     */
    public function setLastName($lastName)
    {
        $this->lastName = is_null($lastName) ? '' : $lastName;;

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
     * @return SysUser
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
     * @return SysUser
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = is_null($phoneNumber) ? '' : $phoneNumber;;

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
     * Set password
     *
     * @param string $password
     *
     * @return SysUser
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

    /**
     * Add medCheckup
     *
     * @param \AppBundle\Entity\MedCheckup $medCheckup
     *
     * @return SysUser
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
     * Add coaching
     *
     * @param \AppBundle\Entity\Coaching $coaching
     *
     * @return SysUser
     */
    public function addCoaching(\AppBundle\Entity\Coaching $coaching)
    {
        $this->coachings[] = $coaching;

        return $this;
    }

    /**
     * Remove coaching
     *
     * @param \AppBundle\Entity\Coaching $coaching
     */
    public function removeCoaching(\AppBundle\Entity\Coaching $coaching)
    {
        $this->coachings->removeElement($coaching);
    }

    /**
     * Get coachings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoachings()
    {
        return $this->coachings;
    }

    /**
     * Add patient
     *
     * @param \AppBundle\Entity\Patient $patient
     *
     * @return SysUser
     */
    public function addPatient(\AppBundle\Entity\Patient $patient)
    {
        $this->patients[] = $patient;

        return $this;
    }

    /**
     * Remove patient
     *
     * @param \AppBundle\Entity\Patient $patient
     */
    public function removePatient(\AppBundle\Entity\Patient $patient)
    {
        $this->patients->removeElement($patient);
    }

    /**
     * Get patients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPatients()
    {
        return $this->patients;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return SysUser
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
     * @return SysUser
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
     * @return SysUser
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
     * Add arrangement
     *
     * @param \AppBundle\Entity\Arrangement $arrangement
     *
     * @return SysUser
     */
    public function addArrangement(\AppBundle\Entity\Arrangement $arrangement)
    {
        $this->arrangements[] = $arrangement;

        return $this;
    }

    /**
     * Remove arrangement
     *
     * @param \AppBundle\Entity\Arrangement $arrangement
     */
    public function removeArrangement(\AppBundle\Entity\Arrangement $arrangement)
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
     * Set hospital
     *
     * @param \AppBundle\Entity\Hospital $hospital
     *
     * @return SysUser
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
}
