<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ArrangementRepository")
 * @ORM\Table(name="arrangement") 
 */
class Arrangement
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
    * @ORM\ManyToOne(targetEntity="SysUser", inversedBy="arrangements") 
    * @ORM\JoinColumn(name="sys_user_id", referencedColumnName="id") 
    */
    private $sysUser;

    /**
     * @ORM\Column(type="string", length=255, options={"default" : ""})
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Der Name darf nicht länger als {{ limit }} Zeichen sein."
     * )
     * @Assert\NotBlank()
     */
    private $name;
    
    /**
     * @ORM\Column(type="string", length=1023, options={"default" : ""})
     * @Assert\Length(
     *      max = 1023,
     *      maxMessage = "Die Beschreibung darf nicht länger als {{ limit }} Zeichen sein."
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(
     *    message="Der Wert {{ value }} ist kein gültiges Datum"
     * )
     * @Assert\NotBlank()
     */
    private $dateTime;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(
     *     type="integer",
     *     message="Der Wert {{ value }} ist keine gültige {{ type }}."
     * )
     * @Assert\NotBlank(
     *     message = "Muss mehr als 0 sein."
     * )
     * @Assert\GreaterThanOrEqual(
     *     value = 1,
     *     message = "Muss mehr als 0 sein."
     * )
     */
    private $maxParticipants;

    /**
    * @ORM\OneToMany(targetEntity="PatientArrangementReference", mappedBy="arrangement") 
    */
    private $patArrRefs;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->patArrRefs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add patArrRef
     *
     * @param \AppBundle\Entity\PatientArrangementReference $patArrRef
     *
     * @return Arrangement
     */
    public function addPatArrRef(\AppBundle\Entity\PatientArrangementReference $patArrRef)
    {
        $this->patArrRefs[] = $patArrRef;

        return $this;
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
     * Set name
     *
     * @param string $name
     *
     * @return Arrangement
     */
    public function setName($name)
    {
        $this->name = is_null($name) ? '' : $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Arrangement
     */
    public function setDescription($description)
    {
        $this->description = is_null($description) ? '' : $description;;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     *
     * @return Arrangement
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
     * Set maxParticipants
     *
     * @param integer $maxParticipants
     *
     * @return Arrangement
     */
    public function setMaxParticipants($maxParticipants)
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    /**
     * Get maxParticipants
     *
     * @return integer
     */
    public function getMaxParticipants()
    {
        return $this->maxParticipants;
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

    /**
     * Set sysUser
     *
     * @param \AppBundle\Entity\SysUser $sysUser
     *
     * @return Arrangement
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
            return (string)$this->getName().' (id = '.(string)$this->getId().')';
        } catch (Exception $e) {
           return get_class($this).'@'.spl_object_hash($this); // If it is not possible, return a preset string to identify instance of object, e.g.
        }
        
    
    }
}
