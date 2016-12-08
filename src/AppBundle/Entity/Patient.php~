<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\PersistentCollection;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PatientRepository")
 * @ORM\Table(name="patient") 
 * @UniqueEntity(
 *     fields={"email"},
 *     groups = {"create", "edit"},
 *     message="Diese E-Mail wird bereits verwendet"
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
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Der Vorname darf nicht länger als {{ limit }} Zeichen sein."
     * )     
     * @Assert\NotBlank(
     *      groups = {"create", "edit"},
     *      message = "Der Vorname darf nicht leer sein."
     * )
     */
    private $firstName;
    
    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      groups = {"create", "edit"},
     *      max = 50,
     *      maxMessage = "Der Nachname darf nicht länger als {{ limit }} Zeichen sein."
     * )
     * @Assert\NotBlank(
     *      groups = {"create", "edit"},
     *      message = "Der Vorname darf nicht leer sein."
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="date", length=50, nullable=true)
     * @Assert\Date(
     *      groups = {"create", "edit"},
     *      message = "Dieser Wert '{{ value }}' ist kein gültiges Datum."
     * )
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})     
     */
    private $sex;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})  
     * @Assert\Length(
     *      groups = {"create", "edit"},
     *      max = 500,
     *      maxMessage = "Die Adresse darf nicht länger als {{ limit }} Zeichen sein."
     * )   
     */
    private $address;


    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\Email(
     *     groups = {"create", "edit"},
     *     message = "Der Wert '{{ value }}' ist keine gültige E-Mail.",
     *     checkMX = true,
     * )
     * @Assert\Length(
     *     max = 150,
     *     maxMessage = "Die E-Mail darf nicht länger als {{ limit }} Zeichen sein.",
     *     groups={"create", "edit"}
     * )  
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=25, options={"default" : ""})
     * @Assert\Length(
     *      groups={"create", "edit"},
     *      min = 4,
     *      max = 25,
     *      minMessage = "Die Nummer darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Die Nummer darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $phoneNumber;


   
    /**
    * @ORM\OneToMany(targetEntity="MedCheckup", mappedBy="patient") 
    */
    private $medCheckups;

    /**
    * @ORM\OneToMany(targetEntity="Coaching", mappedBy="patient") 
    */
    private $coachings;

    /**
    * @ORM\OneToMany(targetEntity="PatientArrangementReference", mappedBy="patient") 
    */
    private $patArrRefs;
     

    /**
     * @ORM\ManyToOne(targetEntity="SysUser", inversedBy="patients") 
     * @ORM\JoinColumn(name="sys_user_id", referencedColumnName="id") 
     */
    private $sysUser;
    
    /**
     * @ORM\ManyToOne(targetEntity="Hospital", inversedBy="patients") 
     * @ORM\JoinColumn(name="hospital_id", referencedColumnName="id") 
     * @Assert\NotBlank(
     *      groups = {"create", "edit"},
     *      message = "Ein Krankenhaus muss definiert werden."
     * )
     */
    private $hospital;



    /**
     * @ORM\Column(type="string", length=200, options={"default" : ""})
     */
    private $krankenversicherungsart;


    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Die Nummer darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Die Nummer darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $krankenkassennummer;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Das Feld darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Das Feld darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $krankenkasse;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Das Feld darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Das Feld darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $kassennameZurBedruckung;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Die Nummer darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Die Nummer darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $versichertennummer;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Die Nummer darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Die Nummer darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $egkVersichertenNr;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Das Feld darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Das Feld darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $kostentraegerabrechnungsbereich;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Das Feld darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Das Feld darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $kvBereich;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Das Feld darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Das Feld darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $abrechnungsvknr;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Das Feld darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Das Feld darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $sonstige;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Das Feld darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Das Feld darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $versichertenartmfr;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Das Feld darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Das Feld darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $versichertenstatuskvk;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Das Feld darf nicht kürzer als {{ limit }} Zeichen sein",
     *      maxMessage = "Das Feld darf nicht länger als {{ limit }} Zeichen sein",
     * )
     */
    private $statusergaenzung;


    /**
     * @ORM\Column(type="date", length=50, nullable=true)
     * @Assert\Date()
     */
    private $validTill;

    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     */
    private $abrechnungsform;


    /**
     * @ORM\Column(type="string", length=50, options={"default" : ""})
     */
    private $nachsorge;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->medCheckups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->patArrRefs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->coachings = new \Doctrine\Common\Collections\ArrayCollection();
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
        $this->firstName = is_null($firstName) ? '' : $firstName;

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
        $this->lastName = is_null($lastName) ? '' : $lastName;

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
        $this->sex = is_null($sex) ? '' : $sex;

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
        $this->address = is_null($address) ? '' : $address;

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
        $this->phoneNumber = is_null($phoneNumber) ? '' : $phoneNumber;

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

    /**
     * Set krankenversicherungsart
     *
     * @param string $krankenversicherungsart
     *
     * @return Patient
     */
    public function setKrankenversicherungsart($krankenversicherungsart)
    {
        $this->krankenversicherungsart = is_null($krankenversicherungsart) ? '' : $krankenversicherungsart;

        return $this;
    }

    /**
     * Get krankenversicherungsart
     *
     * @return string
     */
    public function getKrankenversicherungsart()
    {
        return $this->krankenversicherungsart;
    }

    /**
     * Set krankenkassennummer
     *
     * @param string $krankenkassennummer
     *
     * @return Patient
     */
    public function setKrankenkassennummer($krankenkassennummer)
    {
        $this->krankenkassennummer = is_null($krankenkassennummer) ? '' : $krankenkassennummer;

        return $this;
    }

    /**
     * Get krankenkassennummer
     *
     * @return string
     */
    public function getKrankenkassennummer()
    {
        return $this->krankenkassennummer;
    }

    /**
     * Set krankenkasse
     *
     * @param string $krankenkasse
     *
     * @return Patient
     */
    public function setKrankenkasse($krankenkasse)
    {
        $this->krankenkasse = is_null($krankenkasse) ? '' : $krankenkasse;

        return $this;
    }

    /**
     * Get krankenkasse
     *
     * @return string
     */
    public function getKrankenkasse()
    {
        return $this->krankenkasse;
    }

    /**
     * Set kassennameZurBedruckung
     *
     * @param string $kassennameZurBedruckung
     *
     * @return Patient
     */
    public function setKassennameZurBedruckung($kassennameZurBedruckung)
    {
        $this->kassennameZurBedruckung = is_null($kassennameZurBedruckung) ? '' : $kassennameZurBedruckung;

        return $this;
    }

    /**
     * Get kassennameZurBedruckung
     *
     * @return string
     */
    public function getKassennameZurBedruckung()
    {
        return $this->kassennameZurBedruckung;
    }

    /**
     * Set versichertennummer
     *
     * @param string $versichertennummer
     *
     * @return Patient
     */
    public function setVersichertennummer($versichertennummer)
    {
        $this->versichertennummer = is_null($versichertennummer) ? '' : $versichertennummer;

        return $this;
    }

    /**
     * Get versichertennummer
     *
     * @return string
     */
    public function getVersichertennummer()
    {
        return $this->versichertennummer;
    }

    /**
     * Set egkVersichertenNr
     *
     * @param string $egkVersichertenNr
     *
     * @return Patient
     */
    public function setEgkVersichertenNr($egkVersichertenNr)
    {
        $this->egkVersichertenNr = is_null($egkVersichertenNr) ? '' : $egkVersichertenNr;

        return $this;
    }

    /**
     * Get egkVersichertenNr
     *
     * @return string
     */
    public function getEgkVersichertenNr()
    {
        return $this->egkVersichertenNr;
    }

    /**
     * Set kostentraegerabrechnungsbereich
     *
     * @param string $kostentraegerabrechnungsbereich
     *
     * @return Patient
     */
    public function setKostentraegerabrechnungsbereich($kostentraegerabrechnungsbereich)
    {
        $this->kostentraegerabrechnungsbereich = is_null($kostentraegerabrechnungsbereich) ? '' : $kostentraegerabrechnungsbereich;

        return $this;
    }

    /**
     * Get kostentraegerabrechnungsbereich
     *
     * @return string
     */
    public function getKostentraegerabrechnungsbereich()
    {
        return $this->kostentraegerabrechnungsbereich;
    }

    /**
     * Set kvBereich
     *
     * @param string $kvBereich
     *
     * @return Patient
     */
    public function setKvBereich($kvBereich)
    {
        $this->kvBereich = is_null($kvBereich) ? '' : $kvBereich;

        return $this;
    }

    /**
     * Get kvBereich
     *
     * @return string
     */
    public function getKvBereich()
    {
        return $this->kvBereich;
    }

    /**
     * Set abrechnungsvknr
     *
     * @param string $abrechnungsvknr
     *
     * @return Patient
     */
    public function setAbrechnungsvknr($abrechnungsvknr)
    {
        $this->abrechnungsvknr = is_null($abrechnungsvknr) ? '' : $abrechnungsvknr;

        return $this;
    }

    /**
     * Get abrechnungsvknr
     *
     * @return string
     */
    public function getAbrechnungsvknr()
    {
        return $this->abrechnungsvknr;
    }

    /**
     * Set sonstige
     *
     * @param string $sonstige
     *
     * @return Patient
     */
    public function setSonstige($sonstige)
    {
        $this->sonstige = is_null($sonstige) ? '' : $sonstige;

        return $this;
    }

    /**
     * Get sonstige
     *
     * @return string
     */
    public function getSonstige()
    {
        return $this->sonstige;
    }

    /**
     * Set versichertenartmfr
     *
     * @param string $versichertenartmfr
     *
     * @return Patient
     */
    public function setVersichertenartmfr($versichertenartmfr)
    {
        $this->versichertenartmfr = is_null($versichertenartmfr) ? '' : $versichertenartmfr;

        return $this;
    }

    /**
     * Get versichertenartmfr
     *
     * @return string
     */
    public function getVersichertenartmfr()
    {
        return $this->versichertenartmfr;
    }

    /**
     * Set versichertenstatuskvk
     *
     * @param string $versichertenstatuskvk
     *
     * @return Patient
     */
    public function setVersichertenstatuskvk($versichertenstatuskvk)
    {
        $this->versichertenstatuskvk = is_null($versichertenstatuskvk) ? '' : $versichertenstatuskvk;

        return $this;
    }

    /**
     * Get versichertenstatuskvk
     *
     * @return string
     */
    public function getVersichertenstatuskvk()
    {
        return $this->versichertenstatuskvk;
    }

    /**
     * Set statusergaenzung
     *
     * @param string $statusergaenzung
     *
     * @return Patient
     */
    public function setStatusergaenzung($statusergaenzung)
    {
        $this->statusergaenzung = is_null($statusergaenzung) ? '' : $statusergaenzung;

        return $this;
    }

    /**
     * Get statusergaenzung
     *
     * @return string
     */
    public function getStatusergaenzung()
    {
        return $this->statusergaenzung;
    }

    /**
     * Set validTill
     *
     * @param \DateTime $validTill
     *
     * @return Patient
     */
    public function setValidTill($validTill)
    {
        $this->validTill = $validTill;

        return $this;
    }

    /**
     * Get validTill
     *
     * @return \DateTime
     */
    public function getValidTill()
    {
        return $this->validTill;
    }

    /**
     * Set abrechnungsform
     *
     * @param string $abrechnungsform
     *
     * @return Patient
     */
    public function setAbrechnungsform($abrechnungsform)
    {
        $this->abrechnungsform = is_null($abrechnungsform) ? '' : $abrechnungsform;

        return $this;
    }

    /**
     * Get abrechnungsform
     *
     * @return string
     */
    public function getAbrechnungsform()
    {
        return $this->abrechnungsform;
    }

    /**
     * Set nachsorge
     *
     * @param string $nachsorge
     *
     * @return Patient
     */
    public function setNachsorge($nachsorge)
    {
        $this->nachsorge = is_null($nachsorge) ? '' : $nachsorge;

        return $this;
    }

    /**
     * Get nachsorge
     *
     * @return string
     */
    public function getNachsorge()
    {
        return $this->nachsorge;
    }

    /**
     * Add coaching
     *
     * @param \AppBundle\Entity\Coaching $coaching
     *
     * @return Patient
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
     * Set coach
     *
     * @param \AppBundle\Entity\SysUser $coach
     *
     * @return Patient
     */
    public function setCoach(\AppBundle\Entity\SysUser $coach = null)
    {
        $this->coach = $coach;

        return $this;
    }

    /**
     * Get coach
     *
     * @return \AppBundle\Entity\SysUser
     */
    public function getCoach()
    {
        return $this->coach;
    }

    /**
     * Add coacher
     *
     * @param \AppBundle\Entity\Coaching $coacher
     *
     * @return Patient
     */
    public function addCoacher(\AppBundle\Entity\Coaching $coacher)
    {
        $this->coacher[] = $coacher;

        return $this;
    }

    /**
     * Remove coacher
     *
     * @param \AppBundle\Entity\Coaching $coacher
     */
    public function removeCoacher(\AppBundle\Entity\Coaching $coacher)
    {
        $this->coacher->removeElement($coacher);
    }

    /**
     * Get coacher
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoacher()
    {
        return $this->coacher;
    }

    /**
     * Set sysUser
     *
     * @param \AppBundle\Entity\SysUser $sysUser
     *
     * @return Patient
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
            return (string)$this->getFirstName().' '.(string)$this->getLastName().' (id = '.(string)$this->getId().')';
        } catch (Exception $e) {
           return get_class($this).'@'.spl_object_hash($this); // If it is not possible, return a preset string to identify instance of object, e.g.
        }
        
    
    }
}
