<?php

namespace AppBundle\Controller;

use AppBundle\Controller\TokenAuthenticatedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\TextareaType; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use AppBundle\Entity\SysUser;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Arrangement;
use AppBundle\Entity\MedCheckup;
use AppBundle\Entity\PatientArrangementReference;
use AppBundle\Controller\Util;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PatientController extends Controller
{
    
    /**
     * @Route("/patients", name="patientsPage")
     */
    public function patientsAction(Request $request)
    {        
        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findRelevantToUser($request->attributes->get('user'));

        return $this->render('patient/patientsPage.html.twig', 
            array(
                'title' => 'AOK | Patienten',
                'user' => $request->attributes->get('user'),
                'patients' => $patients,
            )
        );
    }

    /**
     * @Route("/patients/create", name="patientCreatePage")
     */
    public function patientCreateAction(Request $request)
    {        
        $patient = new Patient();
        // Snapshot for logging
        $before = clone($patient);

        $hospitals = $this->getDoctrine()->getRepository('AppBundle:Hospital')->findAll();
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 5));

        $form = $this->createFormBuilder($patient, array('validation_groups' => array('registration'),))
            ->add('firstName', TextType::class, array(
                'label' => 'Vorname', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('lastName', TextType::class, array(
                'label' => 'Nachname', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Geburtstag',
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
            ])
            ->add('sex', ChoiceType::class, array(
                'label' => 'Geschlecht', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control'),                
                'choices'  => array(
                    'männlich' => 'männlich',
                    'weiblich' => 'weiblich',
                ),
                'placeholder' => 'Wählen Sie ein Geschlecht aus',
            ))
            ->add('email', TextType::class, array(
                'label' => 'E-Mail', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'required' => false
            ))
            ->add('phoneNumber', TextType::class, array(
                'label' => 'Tel. Nummer', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array(
                'label' => 'Adresse', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('hospital', ChoiceType::class, array(
                'label' => 'Krankenhaus',
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $hospitals,
                'choice_label' => function($hospital, $key, $index) {
                    return $hospital->getName();
                },                
                'placeholder' => 'Wählen Sie ein Krankenhaus aus',
            ))
            ->add('sysUser', ChoiceType::class, array(
                'label' => 'Betreuer', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $sysUsers,
                'choice_label' => function($sysUser, $key, $index) {
                    return $sysUser->getFirstName().' '.$sysUser->getLastName();
                },                
                'placeholder' => 'Wählen Sie ein Betreuer aus',
            ))

            // Ensurance.
            ->add('krankenversicherungsart', ChoiceType::class, array(
                'label' => 'Krankenversicherungsart', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%'),                
                'choices'  => array(
                    'Gesetzliche Krankenversicherung (GKV)' => 'Gesetzliche Krankenversicherung (GKV)',
                    'Private Krankenversicherung (GKV)' => 'Private Krankenversicherung (GKV)',
                    'Selbstzahler' => 'Selbstzahler',
                    'Unbekannt' => 'Unbekannt',
                ),
                'placeholder' => 'Wählen Sie eine Krankenversicherungsart aus',
            ))
            ->add('krankenkassennummer', TextType::class, array(
                'label' => 'Krankenkassennummer-IK', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('krankenkasse', TextType::class, array(
                'label' => 'Krankenkasse', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('kassennameZurBedruckung', TextType::class, array(
                'label' => 'Kassenname zur Bedruckung', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('versichertennummer', TextType::class, array(
                'label' => 'Versichertennummer', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('egkVersichertenNr', TextType::class, array(
                'label' => 'eGK-Versicherten-Nr', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('kostentraegerabrechnungsbereich', TextType::class, array(
                'label' => 'Kostenträgerabrechnungsbereich', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('kvBereich', TextType::class, array(
                'label' => 'KV-Bereich', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('abrechnungsvknr', TextType::class, array(                                                                                    
                'label' => 'Abrechnungs-VKNR', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('sonstige', TextType::class, array(                                                                                                    
                'label' => 'Sonstige Kostenträger-Zusatzangabe', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('versichertenartmfr', TextType::class, array(                                                                                                                        
                'label' => 'Versichertenart-MFR', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('versichertenstatuskvk', TextType::class, array(                       
                'label' => 'Versichertenstatus-KVK', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('statusergaenzung', TextType::class, array(            
                'label' => 'Statusergänzung', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))

            ->add('validTill', DateType::class, [
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'KV gültig bis (Monat/Jahr)',
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
            ])


           ->add('abrechnungsform', ChoiceType::class, array(
                'label' => 'Abrechnungsform', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%'),                
                'choices'  => array(
                    'Rechnung' => 'Rechnung',
                    'prästationär' => 'prästationär',
                    'privat' => 'privat',
                    'integrierte' => 'integrierte',
                ),
                'placeholder' => 'Wählen Sie eine Abrechnungsform aus',
            ))
            ->add('nachsorge', ChoiceType::class, array(
                'label' => 'Bezahlung der Nachsorge', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%'),                
                'choices'  => array(
                    'wird von der Krankenkasse übernommen' => 'wird von der Krankenkasse übernommen',
                    'wird nicht von der Krankenkasse übernommen' => 'wird nicht von der Krankenkasse übernommen',
                    'Selbstzahler' => 'Selbstzahler',
                    'Unbekannt' => 'Unbekannt',
                ),
                'placeholder' => 'Wählen Sie die Bezahlung der Nachsorge aus',
            ))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $patient->setFirstName($form['firstName']->getData());
            $patient->setLastName($form['lastName']->getData());
            $patient->setBirthDate($form['birthDate']->getData());
            $patient->setSex($form['sex']->getData());        
            $patient->setEmail($form['email']->getData());
            $patient->setPhoneNumber($form['phoneNumber']->getData());
            $patient->setAddress($form['address']->getData());
            $patient->setHospital($form['hospital']->getData());
            $patient->setSysUser($form['sysUser']->getData());

            $patient->setKrankenversicherungsart($form['krankenversicherungsart']->getData());
            $patient->setKrankenkassennummer($form['krankenkassennummer']->getData());
            $patient->setKrankenkasse($form['krankenkasse']->getData());
            $patient->setKassennameZurBedruckung($form['kassennameZurBedruckung']->getData());
            $patient->setVersichertennummer($form['versichertennummer']->getData());
            $patient->setEgkVersichertenNr($form['egkVersichertenNr']->getData());
            $patient->setKostentraegerabrechnungsbereich($form['kostentraegerabrechnungsbereich']->getData());
            $patient->setKvBereich($form['kvBereich']->getData());
            $patient->setAbrechnungsvknr($form['abrechnungsvknr']->getData());
            $patient->setSonstige($form['sonstige']->getData());
            $patient->setVersichertenartmfr($form['versichertenartmfr']->getData());
            $patient->setVersichertenstatuskvk($form['versichertenstatuskvk']->getData());
            $patient->setStatusergaenzung($form['statusergaenzung']->getData());
            $patient->setValidTill($form['validTill']->getData());
            $patient->setAbrechnungsform($form['abrechnungsform']->getData());
            $patient->setNachsorge($form['nachsorge']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();

            $util->logAction($request, $patient->getId(), $before, $patient);
            
            $this->addFlash('notice', 'Ein neuer Patient erfolgreich hinzugefügt');
            
            return $this->redirectToRoute('patientsPage');
        }
        
        $return = $this->render('patient/patientCreatePage.html.twig', array(
            'title' => 'AOK | Patienten | Erstellen',
            'form' => $form->createView(),
            'user' => $request->attributes->get('user'),
            'validation_groups' => array('registration')
        ));

        return $return;
    }
    

    /**
     * @Route("/patients/info/{id}", name="patientInfoPage")
     */
    public function patientInfoAction(Request $request, $id)
    {
        $patient = $this->getDoctrine()->getRepository('AppBundle:Patient')->findOneById($id);        

        $patArrRefs = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findByPatient($id);
        $arrangements = array();
        foreach ($patArrRefs as $key => $value) {
            array_push($arrangements, $value->getArrangement());
        }

        return $this->render('patient/patientInfoPage.html.twig', 
            array(
                'title' => 'AOK | Patienten | Info',
                'user' => $request->attributes->get('user'),
                'arrangements' => $arrangements,
                'patient' => $patient,
            )
        );
    }


    /**
     * @Route("/patients/edit/{id}", name="patientEditPage")
     */
    public function patientEditAction(Request $request, $id)
    {        
        $patient = $this->getDoctrine()->getRepository('AppBundle:Patient')->findOneById($id);
        // Snapshot for logging
        $before = clone($patient);
        
        $hospitals = $this->getDoctrine()->getRepository('AppBundle:Hospital')->findAll();
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 5));   

        $form = $this->createFormBuilder($patient, array('validation_groups' => array('registration'),))
            ->add('firstName', TextType::class, array(
                'label' => 'Vorname', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('lastName', TextType::class, array(
                'label' => 'Nachname', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Geburtstag',
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
            ])
            ->add('sex', ChoiceType::class, array(
                'label' => 'Geschlecht', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control'),                
                'choices'  => array(
                    'männlich' => 'männlich',
                    'weiblich' => 'weiblich',
                ),
                'placeholder' => 'Wählen Sie ein Geschlecht aus',
            ))
            ->add('email', TextType::class, array(
                'label' => 'E-Mail', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'required' => false
            ))
            ->add('phoneNumber', TextType::class, array(
                'label' => 'Tel. Nummer', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array(
                'label' => 'Adresse', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('hospital', ChoiceType::class, array(
                'label' => 'Krankenhaus',
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $hospitals,
                'choice_label' => function($hospital, $key, $index) {
                    return $hospital->getName();
                },                
                'placeholder' => 'Wählen Sie ein Krankenhaus aus',
            ))
            ->add('sysUser', ChoiceType::class, array(
                'label' => 'Betreuer', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $sysUsers,
                'choice_label' => function($sysUser, $key, $index) {
                    return $sysUser->getFirstName().' '.$sysUser->getLastName();
                },                
                'placeholder' => 'Wählen Sie ein Betreuer aus',
            ))

            // Ensurance.
            ->add('krankenversicherungsart', ChoiceType::class, array(
                'label' => 'Krankenversicherungsart', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%'),                
                'choices'  => array(
                    'Gesetzliche Krankenversicherung (GKV)' => 'Gesetzliche Krankenversicherung (GKV)',
                    'Private Krankenversicherung (GKV)' => 'Private Krankenversicherung (GKV)',
                    'Selbstzahler' => 'Selbstzahler',
                    'Unbekannt' => 'Unbekannt',
                ),
                'placeholder' => 'Wählen Sie eine Krankenversicherungsart aus',
            ))
            ->add('krankenkassennummer', TextType::class, array(
                'label' => 'Krankenkassennummer-IK', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('krankenkasse', TextType::class, array(
                'label' => 'Krankenkasse', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('kassennameZurBedruckung', TextType::class, array(
                'label' => 'Kassenname zur Bedruckung', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('versichertennummer', TextType::class, array(
                'label' => 'Versichertennummer', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('egkVersichertenNr', TextType::class, array(
                'label' => 'eGK-Versicherten-Nr', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('kostentraegerabrechnungsbereich', TextType::class, array(
                'label' => 'Kostenträgerabrechnungsbereich', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('kvBereich', TextType::class, array(
                'label' => 'KV-Bereich', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('abrechnungsvknr', TextType::class, array(                                                                                    
                'label' => 'Abrechnungs-VKNR', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('sonstige', TextType::class, array(                                                                                                    
                'label' => 'Sonstige Kostenträger-Zusatzangabe', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('versichertenartmfr', TextType::class, array(                                                                                                                        
                'label' => 'Versichertenart-MFR', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('versichertenstatuskvk', TextType::class, array(                       
                'label' => 'Versichertenstatus-KVK', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('statusergaenzung', TextType::class, array(            
                'label' => 'Statusergänzung', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))

            ->add('validTill', DateType::class, [
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'KV gültig bis (Monat/Jahr)',
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
            ])


           ->add('abrechnungsform', ChoiceType::class, array(
                'label' => 'Abrechnungsform', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%'),                
                'choices'  => array(
                    'Rechnung' => 'Rechnung',
                    'prästationär' => 'prästationär',
                    'privat' => 'privat',
                    'integrierte' => 'integrierte',
                ),
                'placeholder' => 'Wählen Sie eine Abrechnungsform aus',
            ))
            ->add('nachsorge', ChoiceType::class, array(
                'label' => 'Bezahlung der Nachsorge', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%'),                
                'choices'  => array(
                    'wird von der Krankenkasse übernommen' => 'wird von der Krankenkasse übernommen',
                    'wird nicht von der Krankenkasse übernommen' => 'wird nicht von der Krankenkasse übernommen',
                    'Selbstzahler' => 'Selbstzahler',
                    'Unbekannt' => 'Unbekannt',
                ),
                'placeholder' => 'Wählen Sie die Bezahlung der Nachsorge aus',
            ))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $patient->setFirstName($form['firstName']->getData());
            $patient->setLastName($form['lastName']->getData());
            $patient->setBirthDate($form['birthDate']->getData());
            $patient->setSex($form['sex']->getData());        
            $patient->setEmail($form['email']->getData());
            $patient->setPhoneNumber($form['phoneNumber']->getData());
            $patient->setAddress($form['address']->getData());
            $patient->setHospital($form['hospital']->getData());
            $patient->setSysUser($form['sysUser']->getData());

            $patient->setKrankenversicherungsart($form['krankenversicherungsart']->getData());
            $patient->setKrankenkassennummer($form['krankenkassennummer']->getData());
            $patient->setKrankenkasse($form['krankenkasse']->getData());
            $patient->setKassennameZurBedruckung($form['kassennameZurBedruckung']->getData());
            $patient->setVersichertennummer($form['versichertennummer']->getData());
            $patient->setEgkVersichertenNr($form['egkVersichertenNr']->getData());
            $patient->setKostentraegerabrechnungsbereich($form['kostentraegerabrechnungsbereich']->getData());
            $patient->setKvBereich($form['kvBereich']->getData());
            $patient->setAbrechnungsvknr($form['abrechnungsvknr']->getData());
            $patient->setSonstige($form['sonstige']->getData());
            $patient->setVersichertenartmfr($form['versichertenartmfr']->getData());
            $patient->setVersichertenstatuskvk($form['versichertenstatuskvk']->getData());
            $patient->setStatusergaenzung($form['statusergaenzung']->getData());
            $patient->setValidTill($form['validTill']->getData());
            $patient->setAbrechnungsform($form['abrechnungsform']->getData());
            $patient->setNachsorge($form['nachsorge']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);            
            $em->flush();

            // Log differences.
            $util->logAction($request, $id, $before, $patient);
            
            $this->addFlash('notice', 'Patient erfolgreich gespeichert');
            
            return $this->redirectToRoute('patientsPage');
        }
        
        return $this->render('patient/patientEditPage.html.twig', array(
            'title' => 'AOK | Patienten | Bearbeiten',
            'form' => $form->createView(),
            'user' => $request->attributes->get('user'),
            'validation_groups' => array('registration')
        ));
        
    }


    /**
     * @Route("/patients/delete/{id}", name="patientDeletePage")
     */
    public function patientDeleteAction(Request $request, $id)
    {
        $patient = $this->getDoctrine()->getRepository('AppBundle:Patient')->findOneById($id);
        // Snapshot for logging
        $before = clone($patient);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($patient);
        $em->flush();

        // Log differences.
        $util->logAction($request, $id, $before, $patient);
        
        $this->addFlash('notice', 'Patient erfolgreich gelöscht');
        
        return $this->redirectToRoute('patientsPage');
        
    }


}
