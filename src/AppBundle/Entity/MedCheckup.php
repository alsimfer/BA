<?php

/**
 * $ php bin/console doctrine:generate:entities AppBundle/Entity/
 * $ php bin/console doctrine:schema:update --force
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\PersistentCollection;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MedCheckupRepository")
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
    * @ORM\JoinColumn(name="patient_id", referencedColumnName="id", onDelete="CASCADE") 
    * @Assert\NotBlank(
    *    message="Dieses Feld muss ausgefüllt werden."
    * )
    */
    private $patient;

    /**
    * @ORM\ManyToOne(targetEntity="SysUser", inversedBy="medCheckups") 
    * @ORM\JoinColumn(name="sys_user_id", referencedColumnName="id", onDelete="CASCADE") 
    * @Assert\NotBlank(
    *    message="Dieses Feld muss ausgefüllt werden."
    * )
    */
    private $sysUser;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "Der Name darf nicht laenger als {{ limit }} Zeichen sein."
     * )
     * @Assert\NotBlank(
     *    message="Dieses Feld muss ausgefüllt werden."
     * )
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(
     *     message="Der Wert {{ value }} ist kein gültiges Datum"
     * )
     * @Assert\NotBlank(
     *    message="Dieses Feld muss ausgefüllt werden."
     * )
     */
    private $dateAndTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weight;    

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $waist;   

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hips;   

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $source;

    /**
     * @ORM\Column(type="boolean")
     */
    private $arterielleHypertonie;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $arterielleHypertonieText;

    /**
     * @ORM\Column(type="boolean")
     */
    private $andereKardialeKomorbiditaeten;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $andereKardialeKomorbiditaetenText;

    /**
     * @ORM\Column(type="boolean")
     */
    private $insulinpflichtigerDiabetes;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $insulinpflichtigerDiabetesText;    

    /**
     * @ORM\Column(type="boolean")
     */
    private $nichtInsulinpflichtigerDiabetes;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $nichtInsulinpflichtigerDiabetesText;    

    /**
     * @ORM\Column(type="boolean")
     */
    private $pulmonaleKomorbiditaeten;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $pulmonaleKomorbiditaetenText;    

    /**
     * @ORM\Column(type="boolean")
     */
    private $fettstoffwechselstoerungen;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $fettstoffwechselstoerungenText;  

    /**
     * @ORM\Column(type="boolean")
     */
    private $endokrineKomorbiditaeten;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $endokrineKomorbiditaetenText;  

    /**
     * @ORM\Column(type="boolean")
     */
    private $gastroenterologischeKomorbiditaeten;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $gastroenterologischeKomorbiditaetenText;  

    /**
     * @ORM\Column(type="boolean")
     */
    private $varikosis;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $varikosisText;  

    /**
     * @ORM\Column(type="boolean")
     */
    private $orthopaedischeKomorbiditaeten;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $orthopaedischeKomorbiditaetenText;  

    /**
     * @ORM\Column(type="boolean")
     */
    private $neurologischeKomorbiditaeten;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $neurologischeKomorbiditaetenText;  

    /**
     * @ORM\Column(type="boolean")
     */
    private $renaleKomorbiditaeten;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $renaleKomorbiditaetenText;  

    /**
     * @ORM\Column(type="boolean")
     */
    private $oedeme;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $oedemeText;  

    /**
     * @ORM\Column(type="boolean")
     */
    private $organtransplantation;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $organtransplantationText;  

    /**
     * @ORM\Column(type="boolean")
     */
    private $praderWilliSyndrom;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $praderWilliSyndromText;  


    /**
     * @ORM\Column(type="boolean")
     */
    private $nikotinabusus;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $nikotinabususText;

    /**
     * @ORM\Column(type="boolean")
     */
    private $alkoholabusus;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $alkoholabususText;

    /**
     * @ORM\Column(type="boolean")
     */
    private $weiteres;

    /**
     * @ORM\Column(type="string", length=500, options={"default" : ""})
     */
    private $weiteresText;

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
     * Set source
     *
     * @param string $source
     *
     * @return MedCheckup
     */
    public function setSource($source)
    {
        $this->source = is_null($source) ? '' : $source;

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
     * Set arterielleHypertonie
     *
     * @param boolean $arterielleHypertonie
     *
     * @return MedCheckup
     */
    public function setArterielleHypertonie($arterielleHypertonie)
    {
        $this->arterielleHypertonie = $arterielleHypertonie;

        return $this;
    }

    /**
     * Get arterielleHypertonie
     *
     * @return boolean
     */
    public function getArterielleHypertonie()
    {
        return $this->arterielleHypertonie;
    }

    /**
     * Set arterielleHypertonieText
     *
     * @param string $arterielleHypertonieText
     *
     * @return MedCheckup
     */
    public function setArterielleHypertonieText($arterielleHypertonieText)
    {
        $this->arterielleHypertonieText = is_null($arterielleHypertonieText) ? '' : $arterielleHypertonieText;;

        return $this;
    }

    /**
     * Get arterielleHypertonieText
     *
     * @return string
     */
    public function getArterielleHypertonieText()
    {
        return $this->arterielleHypertonieText;
    }

    /**
     * Set andereKardialeKomorbiditaeten
     *
     * @param boolean $andereKardialeKomorbiditaeten
     *
     * @return MedCheckup
     */
    public function setAndereKardialeKomorbiditaeten($andereKardialeKomorbiditaeten)
    {
        $this->andereKardialeKomorbiditaeten = $andereKardialeKomorbiditaeten;

        return $this;
    }

    /**
     * Get andereKardialeKomorbiditaeten
     *
     * @return boolean
     */
    public function getAndereKardialeKomorbiditaeten()
    {
        return $this->andereKardialeKomorbiditaeten;
    }

    /**
     * Set andereKardialeKomorbiditaetenText
     *
     * @param string $andereKardialeKomorbiditaetenText
     *
     * @return MedCheckup
     */
    public function setAndereKardialeKomorbiditaetenText($andereKardialeKomorbiditaetenText)
    {
        $this->andereKardialeKomorbiditaetenText = is_null($andereKardialeKomorbiditaetenText) ? '' : $andereKardialeKomorbiditaetenText;

        return $this;
    }

    /**
     * Get andereKardialeKomorbiditaetenText
     *
     * @return string
     */
    public function getAndereKardialeKomorbiditaetenText()
    {
        return $this->andereKardialeKomorbiditaetenText;
    }

    /**
     * Set insulinpflichtigerDiabetes
     *
     * @param boolean $insulinpflichtigerDiabetes
     *
     * @return MedCheckup
     */
    public function setInsulinpflichtigerDiabetes($insulinpflichtigerDiabetes)
    {
        $this->insulinpflichtigerDiabetes = $insulinpflichtigerDiabetes;

        return $this;
    }

    /**
     * Get insulinpflichtigerDiabetes
     *
     * @return boolean
     */
    public function getInsulinpflichtigerDiabetes()
    {
        return $this->insulinpflichtigerDiabetes;
    }

    /**
     * Set insulinpflichtigerDiabetesText
     *
     * @param string $insulinpflichtigerDiabetesText
     *
     * @return MedCheckup
     */
    public function setInsulinpflichtigerDiabetesText($insulinpflichtigerDiabetesText)
    {
        $this->insulinpflichtigerDiabetesText = is_null($insulinpflichtigerDiabetesText) ? '' : $insulinpflichtigerDiabetesText;

        return $this;
    }

    /**
     * Get insulinpflichtigerDiabetesText
     *
     * @return string
     */
    public function getInsulinpflichtigerDiabetesText()
    {
        return $this->insulinpflichtigerDiabetesText;
    }

    /**
     * Set nichtInsulinpflichtigerDiabetes
     *
     * @param boolean $nichtInsulinpflichtigerDiabetes
     *
     * @return MedCheckup
     */
    public function setNichtInsulinpflichtigerDiabetes($nichtInsulinpflichtigerDiabetes)
    {
        $this->nichtInsulinpflichtigerDiabetes = $nichtInsulinpflichtigerDiabetes;

        return $this;
    }

    /**
     * Get nichtInsulinpflichtigerDiabetes
     *
     * @return boolean
     */
    public function getNichtInsulinpflichtigerDiabetes()
    {
        return $this->nichtInsulinpflichtigerDiabetes;
    }

    /**
     * Set nichtInsulinpflichtigerDiabetesText
     *
     * @param string $nichtInsulinpflichtigerDiabetesText
     *
     * @return MedCheckup
     */
    public function setNichtInsulinpflichtigerDiabetesText($nichtInsulinpflichtigerDiabetesText)
    {
        $this->nichtInsulinpflichtigerDiabetesText = $nichtInsulinpflichtigerDiabetesText;

        return $this;
    }

    /**
     * Get nichtInsulinpflichtigerDiabetesText
     *
     * @return string
     */
    public function getNichtInsulinpflichtigerDiabetesText()
    {
        return $this->nichtInsulinpflichtigerDiabetesText;
    }

    /**
     * Set pulmonaleKomorbiditaeten
     *
     * @param boolean $pulmonaleKomorbiditaeten
     *
     * @return MedCheckup
     */
    public function setPulmonaleKomorbiditaeten($pulmonaleKomorbiditaeten)
    {
        $this->pulmonaleKomorbiditaeten = $pulmonaleKomorbiditaeten;

        return $this;
    }

    /**
     * Get pulmonaleKomorbiditaeten
     *
     * @return boolean
     */
    public function getPulmonaleKomorbiditaeten()
    {
        return $this->pulmonaleKomorbiditaeten;
    }

    /**
     * Set pulmonaleKomorbiditaetenText
     *
     * @param string $pulmonaleKomorbiditaetenText
     *
     * @return MedCheckup
     */
    public function setPulmonaleKomorbiditaetenText($pulmonaleKomorbiditaetenText)
    {
        $this->pulmonaleKomorbiditaetenText = $pulmonaleKomorbiditaetenText;

        return $this;
    }

    /**
     * Get pulmonaleKomorbiditaetenText
     *
     * @return string
     */
    public function getPulmonaleKomorbiditaetenText()
    {
        return $this->pulmonaleKomorbiditaetenText;
    }

    /**
     * Set fettstoffwechselstoerungen
     *
     * @param boolean $fettstoffwechselstoerungen
     *
     * @return MedCheckup
     */
    public function setFettstoffwechselstoerungen($fettstoffwechselstoerungen)
    {
        $this->fettstoffwechselstoerungen = $fettstoffwechselstoerungen;

        return $this;
    }

    /**
     * Get fettstoffwechselstoerungen
     *
     * @return boolean
     */
    public function getFettstoffwechselstoerungen()
    {
        return $this->fettstoffwechselstoerungen;
    }

    /**
     * Set fettstoffwechselstoerungenText
     *
     * @param string $fettstoffwechselstoerungenText
     *
     * @return MedCheckup
     */
    public function setFettstoffwechselstoerungenText($fettstoffwechselstoerungenText)
    {
        $this->fettstoffwechselstoerungenText = $fettstoffwechselstoerungenText;

        return $this;
    }

    /**
     * Get fettstoffwechselstoerungenText
     *
     * @return string
     */
    public function getFettstoffwechselstoerungenText()
    {
        return $this->fettstoffwechselstoerungenText;
    }

    /**
     * Set endokrineKomorbiditaeten
     *
     * @param boolean $endokrineKomorbiditaeten
     *
     * @return MedCheckup
     */
    public function setEndokrineKomorbiditaeten($endokrineKomorbiditaeten)
    {
        $this->endokrineKomorbiditaeten = $endokrineKomorbiditaeten;

        return $this;
    }

    /**
     * Get endokrineKomorbiditaeten
     *
     * @return boolean
     */
    public function getEndokrineKomorbiditaeten()
    {
        return $this->endokrineKomorbiditaeten;
    }

    /**
     * Set endokrineKomorbiditaetenText
     *
     * @param string $endokrineKomorbiditaetenText
     *
     * @return MedCheckup
     */
    public function setEndokrineKomorbiditaetenText($endokrineKomorbiditaetenText)
    {
        $this->endokrineKomorbiditaetenText = $endokrineKomorbiditaetenText;

        return $this;
    }

    /**
     * Get endokrineKomorbiditaetenText
     *
     * @return string
     */
    public function getEndokrineKomorbiditaetenText()
    {
        return $this->endokrineKomorbiditaetenText;
    }

    /**
     * Set gastroenterologischeKomorbiditaeten
     *
     * @param boolean $gastroenterologischeKomorbiditaeten
     *
     * @return MedCheckup
     */
    public function setGastroenterologischeKomorbiditaeten($gastroenterologischeKomorbiditaeten)
    {
        $this->gastroenterologischeKomorbiditaeten = $gastroenterologischeKomorbiditaeten;

        return $this;
    }

    /**
     * Get gastroenterologischeKomorbiditaeten
     *
     * @return boolean
     */
    public function getGastroenterologischeKomorbiditaeten()
    {
        return $this->gastroenterologischeKomorbiditaeten;
    }

    /**
     * Set gastroenterologischeKomorbiditaetenText
     *
     * @param string $gastroenterologischeKomorbiditaetenText
     *
     * @return MedCheckup
     */
    public function setGastroenterologischeKomorbiditaetenText($gastroenterologischeKomorbiditaetenText)
    {
        $this->gastroenterologischeKomorbiditaetenText = $gastroenterologischeKomorbiditaetenText;

        return $this;
    }

    /**
     * Get gastroenterologischeKomorbiditaetenText
     *
     * @return string
     */
    public function getGastroenterologischeKomorbiditaetenText()
    {
        return $this->gastroenterologischeKomorbiditaetenText;
    }

    /**
     * Set varikosis
     *
     * @param boolean $varikosis
     *
     * @return MedCheckup
     */
    public function setVarikosis($varikosis)
    {
        $this->varikosis = $varikosis;

        return $this;
    }

    /**
     * Get varikosis
     *
     * @return boolean
     */
    public function getVarikosis()
    {
        return $this->varikosis;
    }

    /**
     * Set varikosisText
     *
     * @param string $varikosisText
     *
     * @return MedCheckup
     */
    public function setVarikosisText($varikosisText)
    {
        $this->varikosisText = $varikosisText;

        return $this;
    }

    /**
     * Get varikosisText
     *
     * @return string
     */
    public function getVarikosisText()
    {
        return $this->varikosisText;
    }

    /**
     * Set orthopaedischeKomorbiditaeten
     *
     * @param boolean $orthopaedischeKomorbiditaeten
     *
     * @return MedCheckup
     */
    public function setOrthopaedischeKomorbiditaeten($orthopaedischeKomorbiditaeten)
    {
        $this->orthopaedischeKomorbiditaeten = $orthopaedischeKomorbiditaeten;

        return $this;
    }

    /**
     * Get orthopaedischeKomorbiditaeten
     *
     * @return boolean
     */
    public function getOrthopaedischeKomorbiditaeten()
    {
        return $this->orthopaedischeKomorbiditaeten;
    }

    /**
     * Set orthopaedischeKomorbiditaetenText
     *
     * @param string $orthopaedischeKomorbiditaetenText
     *
     * @return MedCheckup
     */
    public function setOrthopaedischeKomorbiditaetenText($orthopaedischeKomorbiditaetenText)
    {
        $this->orthopaedischeKomorbiditaetenText = $orthopaedischeKomorbiditaetenText;

        return $this;
    }

    /**
     * Get orthopaedischeKomorbiditaetenText
     *
     * @return string
     */
    public function getOrthopaedischeKomorbiditaetenText()
    {
        return $this->orthopaedischeKomorbiditaetenText;
    }

    /**
     * Set neurologischeKomorbiditaeten
     *
     * @param boolean $neurologischeKomorbiditaeten
     *
     * @return MedCheckup
     */
    public function setNeurologischeKomorbiditaeten($neurologischeKomorbiditaeten)
    {
        $this->neurologischeKomorbiditaeten = $neurologischeKomorbiditaeten;

        return $this;
    }

    /**
     * Get neurologischeKomorbiditaeten
     *
     * @return boolean
     */
    public function getNeurologischeKomorbiditaeten()
    {
        return $this->neurologischeKomorbiditaeten;
    }

    /**
     * Set neurologischeKomorbiditaetenText
     *
     * @param string $neurologischeKomorbiditaetenText
     *
     * @return MedCheckup
     */
    public function setNeurologischeKomorbiditaetenText($neurologischeKomorbiditaetenText)
    {
        $this->neurologischeKomorbiditaetenText = $neurologischeKomorbiditaetenText;

        return $this;
    }

    /**
     * Get neurologischeKomorbiditaetenText
     *
     * @return string
     */
    public function getNeurologischeKomorbiditaetenText()
    {
        return $this->neurologischeKomorbiditaetenText;
    }

    /**
     * Set renaleKomorbiditaeten
     *
     * @param boolean $renaleKomorbiditaeten
     *
     * @return MedCheckup
     */
    public function setRenaleKomorbiditaeten($renaleKomorbiditaeten)
    {
        $this->renaleKomorbiditaeten = $renaleKomorbiditaeten;

        return $this;
    }

    /**
     * Get renaleKomorbiditaeten
     *
     * @return boolean
     */
    public function getRenaleKomorbiditaeten()
    {
        return $this->renaleKomorbiditaeten;
    }

    /**
     * Set renaleKomorbiditaetenText
     *
     * @param string $renaleKomorbiditaetenText
     *
     * @return MedCheckup
     */
    public function setRenaleKomorbiditaetenText($renaleKomorbiditaetenText)
    {
        $this->renaleKomorbiditaetenText = $renaleKomorbiditaetenText;

        return $this;
    }

    /**
     * Get renaleKomorbiditaetenText
     *
     * @return string
     */
    public function getRenaleKomorbiditaetenText()
    {
        return $this->renaleKomorbiditaetenText;
    }

    /**
     * Set oedeme
     *
     * @param boolean $oedeme
     *
     * @return MedCheckup
     */
    public function setOedeme($oedeme)
    {
        $this->oedeme = $oedeme;

        return $this;
    }

    /**
     * Get oedeme
     *
     * @return boolean
     */
    public function getOedeme()
    {
        return $this->oedeme;
    }

    /**
     * Set oedemeText
     *
     * @param string $oedemeText
     *
     * @return MedCheckup
     */
    public function setOedemeText($oedemeText)
    {
        $this->oedemeText = $oedemeText;

        return $this;
    }

    /**
     * Get oedemeText
     *
     * @return string
     */
    public function getOedemeText()
    {
        return $this->oedemeText;
    }

    /**
     * Set organtransplantation
     *
     * @param boolean $organtransplantation
     *
     * @return MedCheckup
     */
    public function setOrgantransplantation($organtransplantation)
    {
        $this->organtransplantation = $organtransplantation;

        return $this;
    }

    /**
     * Get organtransplantation
     *
     * @return boolean
     */
    public function getOrgantransplantation()
    {
        return $this->organtransplantation;
    }

    /**
     * Set organtransplantationText
     *
     * @param string $organtransplantationText
     *
     * @return MedCheckup
     */
    public function setOrgantransplantationText($organtransplantationText)
    {
        $this->organtransplantationText = $organtransplantationText;

        return $this;
    }

    /**
     * Get organtransplantationText
     *
     * @return string
     */
    public function getOrgantransplantationText()
    {
        return $this->organtransplantationText;
    }

    /**
     * Set praderWilliSyndrom
     *
     * @param boolean $praderWilliSyndrom
     *
     * @return MedCheckup
     */
    public function setPraderWilliSyndrom($praderWilliSyndrom)
    {
        $this->praderWilliSyndrom = $praderWilliSyndrom;

        return $this;
    }

    /**
     * Get praderWilliSyndrom
     *
     * @return boolean
     */
    public function getPraderWilliSyndrom()
    {
        return $this->praderWilliSyndrom;
    }

    /**
     * Set praderWilliSyndromText
     *
     * @param string $praderWilliSyndromText
     *
     * @return MedCheckup
     */
    public function setPraderWilliSyndromText($praderWilliSyndromText)
    {
        $this->praderWilliSyndromText = $praderWilliSyndromText;

        return $this;
    }

    /**
     * Get praderWilliSyndromText
     *
     * @return string
     */
    public function getPraderWilliSyndromText()
    {
        return $this->praderWilliSyndromText;
    }

    /**
     * Set nikotinabusus
     *
     * @param boolean $nikotinabusus
     *
     * @return MedCheckup
     */
    public function setNikotinabusus($nikotinabusus)
    {
        $this->nikotinabusus = $nikotinabusus;

        return $this;
    }

    /**
     * Get nikotinabusus
     *
     * @return boolean
     */
    public function getNikotinabusus()
    {
        return $this->nikotinabusus;
    }

    /**
     * Set nikotinabususText
     *
     * @param string $nikotinabususText
     *
     * @return MedCheckup
     */
    public function setNikotinabususText($nikotinabususText)
    {
        $this->nikotinabususText = $nikotinabususText;

        return $this;
    }

    /**
     * Get nikotinabususText
     *
     * @return string
     */
    public function getNikotinabususText()
    {
        return $this->nikotinabususText;
    }

    /**
     * Set alkoholabusus
     *
     * @param boolean $alkoholabusus
     *
     * @return MedCheckup
     */
    public function setAlkoholabusus($alkoholabusus)
    {
        $this->alkoholabusus = $alkoholabusus;

        return $this;
    }

    /**
     * Get alkoholabusus
     *
     * @return boolean
     */
    public function getAlkoholabusus()
    {
        return $this->alkoholabusus;
    }

    /**
     * Set alkoholabususText
     *
     * @param string $alkoholabususText
     *
     * @return MedCheckup
     */
    public function setAlkoholabususText($alkoholabususText)
    {
        $this->alkoholabususText = $alkoholabususText;

        return $this;
    }

    /**
     * Get alkoholabususText
     *
     * @return string
     */
    public function getAlkoholabususText()
    {
        return $this->alkoholabususText;
    }

    /**
     * Set weiteres
     *
     * @param boolean $weiteres
     *
     * @return MedCheckup
     */
    public function setWeiteres($weiteres)
    {
        $this->weiteres = $weiteres;

        return $this;
    }

    /**
     * Get weiteres
     *
     * @return boolean
     */
    public function getWeiteres()
    {
        return $this->weiteres;
    }

    /**
     * Set weiteresText
     *
     * @param string $weiteresText
     *
     * @return MedCheckup
     */
    public function setWeiteresText($weiteresText)
    {
        $this->weiteresText = $weiteresText;

        return $this;
    }

    /**
     * Get weiteresText
     *
     * @return string
     */
    public function getWeiteresText()
    {
        return $this->weiteresText;
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
            return (string)$this->getId();
        } catch (Exception $e) {
           return get_class($this).'@'.spl_object_hash($this); // If it is not possible, return a preset string to identify instance of object, e.g.
        }
        
    
    }
}
