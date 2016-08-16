<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="hospital") 
 */
class Hospital
{
	/**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Der Name darf nicht länger als {{ limit }} Zeichen sein."
     * )     
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     * @Assert\Length(
     *      max = 500,
     *      maxMessage = "Die Beschreibung darf nicht länger als {{ limit }} Zeichen sein."
     * )
     */
    private $description;

	/**
	* @ORM\OneToMany(targetEntity="Patient", mappedBy="hospital") 
	*/
	private $patients;

    /**
    * @ORM\OneToMany(targetEntity="SysUser", mappedBy="hospital") 
    */
    private $sysUsers;

	public function __construct() 
	{
        $this->patients = new ArrayCollection();
		$this->sysUsers = new ArrayCollection();
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
     * @return Hospital
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
     * @return Hospital
     */
    public function setDescription($description)
    {
        $this->description = is_null($description) ? '' : $description;

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
     * Add patient
     *
     * @param \AppBundle\Entity\Patient $patient
     *
     * @return Hospital
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
     * Add sysUser
     *
     * @param \AppBundle\Entity\SysUser $sysUser
     *
     * @return Hospital
     */
    public function addSysUser(\AppBundle\Entity\SysUser $sysUser)
    {
        $this->sysUsers[] = $sysUser;

        return $this;
    }

    /**
     * Remove sysUser
     *
     * @param \AppBundle\Entity\SysUser $sysUser
     */
    public function removeSysUser(\AppBundle\Entity\SysUser $sysUser)
    {
        $this->sysUsers->removeElement($sysUser);
    }

    /**
     * Get sysUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSysUsers()
    {
        return $this->sysUsers;
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
