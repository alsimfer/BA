<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
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
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Der Name darf nicht länger als {{ limit }} Zeichen sein."
     * )
     */
    private $name = '';
    
    /**
     * @ORM\Column(type="string", length=1023)
     * @Assert\Length(
     *      max = 1023,
     *      maxMessage = "Die Beschreibung darf nicht länger als {{ limit }} Zeichen sein."
     * )
     */
    private $description = '';

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
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
        $this->name = !isset($name) ? '' : $name;

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
        $this->description = !isset($description) ? '' : $description;

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
}
