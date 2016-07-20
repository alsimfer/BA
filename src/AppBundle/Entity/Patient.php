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
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Der Vorname darf nicht länger als {{ limit }} Zeichen sein."
     * )     
     */
    private $firstName = '';
    
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Der Nachname darf nicht länger als {{ limit }} Zeichen sein."
     * )
     */
    private $lastName = '';

    /**
     * @ORM\Column(type="string", unique=true, length=150)
     * @Assert\Email(
     *     message = "Der Wert '{{ value }}' ist keine gültige E-Mail.",
     *     checkMX = true
     * )
     * @Assert\Length(
     *     max = 150,
     *     maxMessage = "Die E-Mail darf nicht länger als {{ limit }} Zeichen sein."
     * )
     * @Assert\NotBlank(groups={"registration"})    
     */
    private $email = '';

    /**
     * @ORM\Column(type="string", length=20)
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

    public function __construct() 
    {
        $this->medCheckups = new ArrayCollection();
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
        $this->firstName = !isset($firstName) ? '' : $firstName;

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
        $this->lastName = !isset($lastName) ? '' : $lastName;

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
        $this->phoneNumber = !isset($phoneNumber) ? '' : $phoneNumber;

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
}
