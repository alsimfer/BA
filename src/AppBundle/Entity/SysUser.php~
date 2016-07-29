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
 *     groups={"registration"},
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
    * @ORM\OneToMany(targetEntity="MedCheckup", mappedBy="sysUser") 
    */
    private $medCheckups;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Der Vorname darf nicht länger als {{ limit }} Zeichen sein."
     * )
     */
    private $firstName;
    
    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Der Nachname darf nicht länger als {{ limit }} Zeichen sein."
     * )
     */
    private $lastName;

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
}
