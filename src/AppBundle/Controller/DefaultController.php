<?php

namespace AppBundle\Controller;

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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="indexPage")
     */
    public function indexAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        return $this->render('default/indexPage.html.twig', 
            array(
                'title' => 'AOK | Index',
                'user' => $sysUser,
                'pageHeader' => 'DB Schema'
            )
        );       
    }


    /**
     * @Route("/patients", name="patientsPage")
     */
    public function patientsAction(Request $request, array $options=null)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findAll();

        return $this->render('patient/patientsPage.html.twig', 
            array(
                'title' => 'AOK | Patienten',
                'user' => $sysUser,
                'patients' => $patients,
            )
        );
    }

    /**
     * @Route("/patients/create", name="patientCreatePage")
     */
    public function patientCreateAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patient = new Patient();
        $hospitals = $this->getDoctrine()->getRepository('AppBundle:Hospital')->findAll();
        $caretakers = $this->getDoctrine()->getRepository('AppBundle:Caretaker')->findAll();

        $form = $this->createFormBuilder($patient, array('validation_groups' => array('registration'),))
            ->add('firstName', TextType::class, array('label' => 'Vorname', 'attr' => array('class' => 'form-control')))
            ->add('lastName', TextType::class, array('label' => 'Nachname', 'attr' => array('class' => 'form-control')))
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Geburtstag',

            ])
            ->add('sex', ChoiceType::class, array('label' => 'Geschlecht', 'attr' => array('class' => 'form-control'),
                'choices'  => array(
                    'männlich' => 'männlich',
                    'weiblich' => 'weiblich',
                ),
                'placeholder' => 'Wählen Sie ein Geschlecht aus',
            ))
            ->add('email', TextType::class, array('label' => 'E-Mail', 
                'attr' => array('class' => 'form-control'),
                'required' => false
            ))
            ->add('phoneNumber', TextType::class, array('label' => 'Tel. Nummer', 'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array('label' => 'Adresse', 'attr' => array('class' => 'form-control')))
            ->add('hospital', ChoiceType::class, array('label' => 'Krankenhaus', 'attr' => array('class' => 'form-control'),
                'choices' => $hospitals,
                'choice_label' => function($hospital, $key, $index) {
                    return $hospital->getName();
                },                
                'placeholder' => 'Wählen Sie ein Krankenhaus aus',
            ))
            ->add('caretaker', ChoiceType::class, array('label' => 'Betreuer', 'attr' => array('class' => 'form-control'),
                'choices' => $caretakers,
                'choice_label' => function($caretaker, $key, $index) {
                    return $caretaker->getFirstName().' '.$caretaker->getLastName();
                },                
                'placeholder' => 'Wählen Sie ein Betreuer aus',
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
            $patient->setCaretaker($form['caretaker']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();

            $this->addFlash('notice', 'Ein neuer Patient erfolgreich hinzugefügt');
            
            return $this->redirectToRoute('patientsPage');
        }
        
        return $this->render('patient/patientCreatePage.html.twig', array(
            'title' => 'AOK | Patienten',
            'form' => $form->createView(),
            'user' => $sysUser,
            'validation_groups' => array('registration')
        ));
        
    }
    

    /**
     * @Route("/patients/info/{id}", name="patientInfoPage")
     */
    public function patientInfoAction(Request $request, $id)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patient = $this->getDoctrine()->getRepository('AppBundle:Patient')->findOneById($id);

        return $this->render('patient/patientInfoPage.html.twig', 
            array(
                'title' => 'AOK | Patienten',
                'user' => $sysUser,
                'patient' => $patient,
            )
        );
    }


    /**
     * @Route("/patients/edit/{id}", name="patientEditPage")
     */
    public function patientEditAction(Request $request, $id)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patient = $this->getDoctrine()->getRepository('AppBundle:Patient')->findOneById($id);
        $hospitals = $this->getDoctrine()->getRepository('AppBundle:Hospital')->findAll();
        $caretakers = $this->getDoctrine()->getRepository('AppBundle:Caretaker')->findAll();
        
        $form = $this->createFormBuilder($patient, array('validation_groups' => array('registration'),))
            ->add('firstName', TextType::class, array('label' => 'Vorname', 'attr' => array('class' => 'form-control')))
            ->add('lastName', TextType::class, array('label' => 'Nachname', 'attr' => array('class' => 'form-control')))
            ->add('birthDate', DateType::class, [
                'label' => 'Geburtstag',
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy',
                'attr' => [
                    'class' => 'form-control'
                ]

            ])
            ->add('sex', ChoiceType::class, array('label' => 'Geschlecht', 'attr' => array('class' => 'form-control'),
                'choices'  => array(
                    'männlich' => 'männlich',
                    'weiblich' => 'weiblich',
                ),
            ))
            ->add('email', TextType::class, array('label' => 'E-Mail', 
                'attr' => array('class' => 'form-control'),
                'required' => false
            ))
            ->add('phoneNumber', TextType::class, array('label' => 'Tel. Nummer', 'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array('label' => 'Adresse', 'attr' => array('class' => 'form-control')))
            ->add('hospital', ChoiceType::class, array('label' => 'Krankenhaus', 'attr' => array('class' => 'form-control'),
                'choices' => $hospitals,
                'choice_label' => function($hospital, $key, $index) {
                    return $hospital->getName();
                },                
                'placeholder' => 'Wählen Sie ein Krankenhaus aus',
            ))
            ->add('caretaker', ChoiceType::class, array('label' => 'Betreuer', 'attr' => array('class' => 'form-control'),
                'choices' => $caretakers,
                'choice_label' => function($caretaker, $key, $index) {
                    return $caretaker->getFirstName().' '.$caretaker->getLastName();
                },                
                'placeholder' => 'Wählen Sie ein Betreuer aus',
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
            $patient->setCaretaker($form['caretaker']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();

            $this->addFlash('notice', 'Patient erfolgreich gespeichert');
            
            return $this->redirectToRoute('patientsPage');
        }
        
        return $this->render('patient/patientEditPage.html.twig', array(
            'title' => 'AOK | Patienten',
            'form' => $form->createView(),
            'user' => $sysUser,
            'validation_groups' => array('registration')
        ));
        
    }


    /**
     * @Route("/patients/delete/{id}", name="patientDeletePage")
     */
    public function patientDeleteAction(Request $request, $id)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patient = $this->getDoctrine()->getRepository('AppBundle:Patient')->findOneById($id);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($patient);
        $em->flush();

        $this->addFlash('notice', 'Patient erfolgreich gelöscht');
        
        return $this->redirectToRoute('patientsPage');
        
    }


    /**
     * @Route("/med-checkups", name="medCheckupsPage")
     */
    public function medCheckupsAction(Request $request, array $options=null)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $medCheckups = $this->getDoctrine()->getRepository('AppBundle:medCheckup')->findAll();

        return $this->render('medCheckup/medCheckupsPage.html.twig', 
            array(
                'title' => 'AOK | Untersuchungen',
                'user' => $sysUser,
                'medCheckups' => $medCheckups,
            )
        );
    }


    /**
     * @Route("/med-checkup/create", name="medCheckupCreatePage")
     */
    public function medCheckupCreateAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $medCheckup = new MedCheckup();
        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findAll();
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 4));
        
        $form = $this->createFormBuilder($medCheckup)
            ->add('type', ChoiceType::class, array('label' => 'Typ', 'attr' => array('class' => 'form-control'),
                'choices'  => array(
                    'Basischeck' => 'Basischeck',
                    'Zwischenuntersuchung' => 'Zwischenuntersuchung',
                    'OP-Untersuchung' => 'OP-Untersuchung',
                ),
                'placeholder' => 'Wählen Sie den Untersuchungstyp aus',
            ))
            ->add('patient', ChoiceType::class, array('label' => 'Patient', 'attr' => array('class' => 'form-control'),
                'choices' => $patients,
                'choice_label' => function($patient, $key, $index) {
                    return $patient->getFirstName().' '.$patient->getLastName();
                },                
                'placeholder' => 'Wählen Sie einen Patient aus',
            ))
            ->add('sysUser', ChoiceType::class, array('label' => 'Untersucher', 'attr' => array('class' => 'form-control'),
                'choices' => $sysUsers,
                'choice_label' => function($sysUser, $key, $index) {
                    return $sysUser->getFirstName().' '.$sysUser->getLastName();
                },                
                'placeholder' => 'Wählen Sie einen Untersucher aus',
            ))
            ->add('dateAndTime', DateTimeType::class, [
                'label' => 'Datum',
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy HH:mm',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            

            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $medCheckup->setType($form['type']->getData());
            $medCheckup->setPatient($form['patient']->getData());
            $medCheckup->setSysUser($form['sysUser']->getData());
            $medCheckup->setDateAndTime($form['dateAndTime']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($medCheckup);
            $em->flush();

            $this->addFlash('notice', 'Eine Untersuchung erfolgreich hinzugefügt');
            
            return $this->redirectToRoute('medCheckupsPage');
        }
        
        return $this->render('medCheckup/medCheckupCreatePage.html.twig', array(
            'title' => 'AOK | Untersuchung',
            'form' => $form->createView(),
            'user' => $sysUser
        ));
        
    }

    /**
     * @Route("/med-checkups/info/{id}", name="medCheckupInfoPage")
     */
    public function medCheckupInfoAction(Request $request, $id)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $medCheckup = $this->getDoctrine()->getRepository('AppBundle:medCheckup')->findOneById($id);

        return $this->render('medCheckup/medCheckupInfoPage.html.twig', 
            array(
                'title' => 'AOK | Patienten',
                'user' => $sysUser,
                'medCheckup' => $medCheckup,
            )
        );
    }


    /**
     * @Route("/med-checkups/edit/{id}", name="medCheckupEditPage")
     */
    public function medCheckupEditAction(Request $request, $id)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $medCheckup = $this->getDoctrine()->getRepository('AppBundle:MedCheckup')->findOneById($id);        
        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findAll();
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 4));
        
        $form = $this->createFormBuilder($medCheckup)
            ->add('type', ChoiceType::class, array('label' => 'Typ', 'attr' => array('class' => 'form-control'),
                'choices'  => array(
                    'Basischeck' => 'Basischeck',
                    'Zwischenuntersuchung' => 'Zwischenuntersuchung',
                    'OP-Untersuchung' => 'OP-Untersuchung',
                ),
                'placeholder' => 'Wählen Sie den Untersuchungstyp aus',
            ))
            ->add('patient', ChoiceType::class, array('label' => 'Patient', 'attr' => array('class' => 'form-control'),
                'choices' => $patients,
                'choice_label' => function($patient, $key, $index) {
                    return $patient->getFirstName().' '.$patient->getLastName();
                },                
                'placeholder' => 'Wählen Sie einen Patient aus',
            ))
            ->add('sysUser', ChoiceType::class, array('label' => 'Untersucher', 'attr' => array('class' => 'form-control'),
                'choices' => $sysUsers,
                'choice_label' => function($sysUser, $key, $index) {
                    return $sysUser->getFirstName().' '.$sysUser->getLastName();
                },                
                'placeholder' => 'Wählen Sie einen Untersucher aus',
            ))
            ->add('dateAndTime', DateTimeType::class, [
                'label' => 'Datum',
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy HH:mm',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            

            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $medCheckup->setType($form['type']->getData());
            $medCheckup->setPatient($form['patient']->getData());
            $medCheckup->setSysUser($form['sysUser']->getData());
            $medCheckup->setDateAndTime($form['dateAndTime']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($medCheckup);
            $em->flush();

            $this->addFlash('notice', 'Eine Untersuchung erfolgreich gespeichert');
            
            return $this->redirectToRoute('medCheckupsPage');
        }
        
        return $this->render('medCheckup/medCheckupEditPage.html.twig', array(
            'title' => 'AOK | Untersuchung',
            'form' => $form->createView(),
            'user' => $sysUser
        ));
        
    }
    

    /**
     * @Route("/med-checkups/delete/{id}", name="medCheckupDeletePage")
     */
    public function medCheckupDeleteAction(Request $request, $id)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $medCheckup = $this->getDoctrine()->getRepository('AppBundle:MedCheckup')->findOneById($id);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($medCheckup);
        $em->flush();

        $this->addFlash('notice', 'Untersuchung erfolgreich gelöscht');
        
        return $this->redirectToRoute('medCheckupsPage');
        
    }


    /**
     * @Route("/arrangements", name="arrangementsPage")
     */
    public function arrangementsAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $arrangements = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findAll();

        return $this->render('arrangement/arrangementsPage.html.twig', 
            array(
                'title' => 'AOK | Kurse',
                'user' => $sysUser,
                'arrangements' => $arrangements,
                'url' => 'arrangements',
                'buttonLabel' => 'Kurs',
                'pageHeader' => 'Übersicht aller Kursen'
            )
        );
    }


    /**
     * @Route("/arrangements/create", name="createArrangementPage")
     */
    public function createArrangementAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $arrangement = new Arrangement();

        $form = $this->createFormBuilder($arrangement)
            ->add('name', TextType::class, array('label' => 'Name', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('description', TextareaType::class, array('label' => 'Beschreibung', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('maxParticipants', IntegerType::class, array('label' => 'Max. Teilnehmer', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('dateTime', DateTimeType::class, [
                'label' => 'Datum und Uhrzeit',
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy HH:mm',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $arrangement->setName($form['name']->getData());
            $arrangement->setDescription($form['description']->getData());
            $arrangement->setDateTime($form['dateTime']->getData());
            $arrangement->setMaxParticipants($form['maxParticipants']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($arrangement);
            $em->flush();

            $this->addFlash('notice', 'Ein neuer Kurs erfolgreich hinzugefügt');
            
            return $this->redirectToRoute('arrangementsPage');
        }
        
        return $this->render('arrangement/arrangementCreatePage.html.twig', array(
            'title' => 'AOK | Kurse',
            'form' => $form->createView(),
            'user' => $sysUser,
            'pageHeader' => 'Kurs einfügen'
        ));
        
    }


    /**
     * @Route("/arrangements/edit/{id}", name="arrangementEditPage")
     */
    public function arrangementEditAction(Request $request, $id)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $arrangement = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findOneById($id);
        
        $form = $this->createFormBuilder($arrangement)
            ->add('name', TextType::class, array('label' => 'Name', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('description', TextareaType::class, array('label' => 'Beschreibung', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('maxParticipants', IntegerType::class, array('label' => 'Max. Teilnehmer', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('dateTime', DateTimeType::class, [
                'label' => 'Datum und Uhrzeit',
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy HH:mm',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $arrangement->setName($form['name']->getData());
            $arrangement->setDescription($form['description']->getData());
            $arrangement->setDateTime($form['dateTime']->getData());
            $arrangement->setMaxParticipants($form['maxParticipants']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($arrangement);
            $em->flush();

            $this->addFlash('notice', 'Kurs erfolgreich gespeichert');

            return $this->redirectToRoute('arrangementsPage');
        }
        
        return $this->render('arrangement/arrangementEditPage.html.twig', array(
            'title' => 'AOK | Kurse',
            'user' => $sysUser,
            'form' => $form->createView(),
            'pageHeader' => 'Kurs bearbeiten'
        ));
    }

    /**
     * @Route("/arrangements/info/{id}", name="arrangementInfoPage")
     */
    public function arrangementInfoAction(Request $request, $id)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $arrangement = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findOneById($id);
        $patArrRefs = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findByArrangement($id);
        $patients = array();
        foreach ($patArrRefs as $key => $value) {
            array_push($patients, $value->getPatient());
        }

        return $this->render('arrangement/arrangementInfoPage.html.twig', 
            array(
                'title' => 'AOK | Kurse',
                'user' => $sysUser,
                'arrangement' => $arrangement,
                'patients' => $patients,
            )
        );
    }


    /**
     * @Route("/arrangements/delete/{id}", name="arrangementDeletePage")
     */
    public function arrangementDeleteAction(Request $request, $id)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $arrangement = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findOneById($id);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($arrangement);
        $em->flush();

        $this->addFlash('notice', 'Kurs erfolgreich gelöscht');
        
        return $this->redirectToRoute('arrangementsPage');
        
    }


    /**
     * @Route("/patient-arrangement", name="patientArrangementPage")
     */
    public function patientArrangementAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patArrRef = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findAll();
    
        return $this->render('default/progressPage.html.twig', 
            array(
                'title' => 'AOK | Kursverlauf',
                'user' => $sysUser,
                'patArrRef' => $patArrRef,
                'url' => 'patient-arrangement',
                'buttonLabel' => 'Kursverlauf',
                'pageHeader' => 'Übersicht aller Kursverläufe'
            )
        );
    }


    /**
     * @Route("/patient-arrangement/create", name="patientArrangementCreatePage")
     */
    public function patientArrangementCreateAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patArrRef = new PatientArrangementReference();
        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findAll();
        $arrangements = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findAll();
        
        $form = $this->createFormBuilder($progress)
            ->add('patient', ChoiceType::class, array('label' => 'Patient', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px;'),
                'choices' => $patients,
                'choice_label' => function($patient, $key, $index) {
                    return $patient->getFirstName().' '.$patient->getLastName();
                },                
            ))
            ->add('arrangement', ChoiceType::class, array('label' => 'Kurs', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px; margin-top: 15px'),
                'choices' => $arrangements,
                'choice_label' => function($arrangement, $key, $index) {
                    return $arrangement->getName().' am '.$arrangement->getDateTime()->format('d.m.Y H:i');;
                },                
            ))
            ->add('registered', ChoiceType::class, array('label' => 'Angemeldet', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px, margin-top: 15px'),
                'choices'  => array(
                    'Ja' => 1,
                    'Nein' => 0,
                ),
            ))
            ->add('attended', ChoiceType::class, array('label' => 'Besucht', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px'),
                'choices'  => array(
                    'Ja' => 1,
                    'Nein' => 0,
                ),
            ))
            ->add('comments', TextareaType::class, array('label' => 'Commentare', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $patArrRef->setPatient($form['patient']->getData());
            $patArrRef->setArrangement($form['arrangement']->getData());
            $patArrRef->setRegistered($form['registered']->getData());
            $patArrRef->setAttended($form['attended']->getData());
            $patArrRef->setComments($form['comments']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($progress);
            $em->flush();

            $this->addFlash('notice', 'Ein neuer Verlaufsweg erfolgreich gespeichert');

            return $this->redirectToRoute('progressPage');
        }
        
        return $this->render('default/editObjectPage.html.twig', array(
            'title' => 'AOK | Kursverlauf',
            'user' => $sysUser,
            'form' => $form->createView(),
            'pageHeader' => 'Kursverlauf erstellen'
        ));
    }


    /**
     * @Route("/patient-arrangement/edit/{id}", name="patientArrangementEditPage")
     */
    public function patientArrangementEditAction(Request $request, $id)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patArrRef = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findOneById($id);
        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findAll();
        $arrangements = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findAll();
        
        $form = $this->createFormBuilder($progress)
            ->add('patient', ChoiceType::class, array('label' => 'Patient', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px;'),
                'choices' => $patients,
                'choice_label' => function($patient, $key, $index) {
                    return $patient->getFirstName().' '.$patient->getLastName();
                },                
            ))
            ->add('arrangement', ChoiceType::class, array('label' => 'Kurs', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px; margin-top: 15px'),
                'choices' => $arrangements,
                'choice_label' => function($arrangement, $key, $index) {
                    return $arrangement->getName().' am '.$arrangement->getDateTime()->format('d.m.Y H:i');;
                },                
            ))
            ->add('registered', ChoiceType::class, array('label' => 'Angemeldet', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px, margin-top: 15px'),
                'choices'  => array(
                    'Ja' => 1,
                    'Nein' => 0,
                ),
            ))
            ->add('attended', ChoiceType::class, array('label' => 'Besucht', 'attr' => array('class' => 'form-control select2','style' => 'margin-bottom: 15px'),
                'choices'  => array(
                    'Ja' => 1,
                    'Nein' => 0,
                ),
            ))
            ->add('comments', TextareaType::class, array('label' => 'Commentare', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $patArrRef->setPatient($form['patient']->getData());
            $patArrRef->setArrangement($form['arrangement']->getData());
            $patArrRef->setRegistered($form['registered']->getData());
            $patArrRef->setAttended($form['attended']->getData());
            $patArrRef->setComments($form['comments']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($patArrRef);
            $em->flush();

            $this->addFlash('notice', 'Verlaufsweg erfolgreich gespeichert');

            return $this->redirectToRoute('patientArrangementPage');
        }
        
        return $this->render('default/editObjectPage.html.twig', array(
            'title' => 'AOK | Kursverlauf',
            'user' => $sysUser,
            'form' => $form->createView(),
            'pageHeader' => 'Kursverlauf bearbeiten'
        ));
    }

   /**
     * @Route("/patient-arrangement/delete/{id}", name="patientArrangementDeletePage")
     */
    public function patientArrangementDeleteAction(Request $request, $id)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        } else if ($sysUser->getUserGroup()->getName() !== "Admin") {
            $this->addFlash('error', 'Sie dürfen nicht einen Kursverlauf löschen!');
            return $this->redirectToRoute('patientArrangementPage');
        }

        $patArrRef = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findOneById($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($patArrRef);
        $em->flush();

        $this->addFlash('notice', 'Kursverlauf erfolgreich gelöscht');
        
        return $this->redirectToRoute('patientArrangementPage');        

    }


    /**
     * @Route("/users", name="usersPage")
     */
    public function usersAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $users = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findAll();

        return $this->render('default/usersPage.html.twig', 
            array(
                'title' => 'AOK | Benutzer',
                'user' => $sysUser,
                'users' => $users,
                'url' => 'users',
                'buttonLabel' => 'Benutzer',
                'pageHeader' => 'Übersicht aller Benutzer'
            )
        );
    }

    /**
     * @Route("/users/create", name="createUserPage")
     */
    public function createUserAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $user = new SysUser();

        // Prepare selectField with userGroups for the form.
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery('select u from AppBundle\Entity\UserGroup u where u.id >= 2');        
        $userGroups = $q->getResult();

        $form = $this->createFormBuilder($user, array('validation_groups' => array('registration'),))
            ->add('firstName', TextType::class, array('label' => 'Vorname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('lastName', TextType::class, array('label' => 'Nachname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('email', EmailType::class, array('label' => 'E-Mail', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('phoneNumber', TextType::class, array('label' => 'Tel. Nummer', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('password', PasswordType::class, array('label' => 'Kennwort', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('userGroup', ChoiceType::class, array('label' => 'Benutzergruppe', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px'),
                'choices' => $userGroups,
                'choice_label' => function($userGroup, $key, $index) {
                    return $userGroup->getDescription();
                },                
            ))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top: 15px'))) 
            ->getForm();

        $form->handleRequest($request);
  
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFirstName($form['firstName']->getData());
            $user->setLastName($form['lastName']->getData());
            $user->setEmail($form['email']->getData());
            $user->setPhoneNumber($form['phoneNumber']->getData());
            $user->setUserGroup($form['userGroup']->getData());
            $user->setPassword(sha1($form['password']->getData()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'Benutzer erfolgreich hinzugefügt');
            $this->sendEmail('Registrierung erfolgreich abgeschlossen', $user, 'email/registration');

            return $this->redirectToRoute('usersPage');
        }
        
        return $this->render('default/editObjectPage.html.twig', array(
            'title' => 'AOK | Benutzer',
            'user' => $sysUser,
            'form' => $form->createView(),
            'pageHeader' => 'Benutzer einfügen',
            
        ));
    }


    /**
     * @Route("/users/edit/{id}", name="userEditPage")
     */
    public function userEditAction(Request $request, $id)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $user = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findOneById($id);
        
        // Prepare selectField with userGroups for the form.
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery('select u from AppBundle\Entity\UserGroup u where u.id >= 2');        
        $userGroups = $q->getResult();

        $form = $this->createFormBuilder($user)
            ->add('firstName', TextType::class, array('label' => 'Vorname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('lastName', TextType::class, array('label' => 'Nachname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('email', EmailType::class, array('label' => 'E-Mail', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('phoneNumber', TextType::class, array('label' => 'Tel. Nummer', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))            
            ->add('userGroup', ChoiceType::class, array('label' => 'Benutzergruppe', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px'),
                'choices' => $userGroups,
                'choice_label' => function($userGroup, $key, $index) {
                    return $userGroup->getDescription();
                },
            ))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFirstName($form['firstName']->getData());
            $user->setLastName($form['lastName']->getData());
            $user->setEmail($form['email']->getData());
            $user->setPhoneNumber($form['phoneNumber']->getData());
            $user->setUserGroup($form['userGroup']->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'Benutzer erfolgreich gespeichert');

            return $this->redirectToRoute('usersPage');
        }
        
        return $this->render('default/editObjectPage.html.twig', array(
            'title' => 'AOK | Benutzer',
            'user' => $sysUser,
            'form' => $form->createView(),
            'pageHeader' => 'Benutzer bearbeiten'
        ));
    }


    /**
     * @Route("/users/delete/{id}", name="userDeletePage")
     */
    public function userDeleteAction(Request $request, $id)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $user = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findOneById($id);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->addFlash('notice', 'Benutzer erfolgreich gelöscht');
        
        return $this->redirectToRoute('usersPage');
        
    }


    /**
     * @Route("/login", name="loginPage")
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        $session->remove('user_id');
        
        $sysUser = new SysUser();

        $form = $this->createFormBuilder($sysUser)
            ->add('email', TextType::class, array('label' => 'E-Mail', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('password', PasswordType::class, array('label' => 'Kennwort', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form['email']->getData();
            $password = sha1($form['password']->getData());            
            
            $sysUser = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findOneBy(
                array('email' => $email, 'password' => $password)
            );
            
            if (!$sysUser) {
                $this->addFlash('notice', 'Kein Benutzer mit eingegebenen E-Mail und Kennwort gefunden');
            } else {
                $session = $request->getSession();
                $session->set('user_id', $sysUser->getId());
                return $this->redirectToRoute('patientsPage');
            }
        }
        
        return $this->render('login/loginPage.html.twig', array( 
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/password-issue", name="passwordPage")
     */
    public function passwordAction(Request $request)
    {
        $session = $request->getSession();
        $session->remove('user_id');
        
        $sysUser = new SysUser();

        $form = $this->createFormBuilder($sysUser)
            ->add('email', TextType::class, array('label' => 'E-Mail', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form['email']->getData();
            
            $sysUser = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findOneBy(
                array('email' => $email)
            );
            
            if (!$sysUser) {
                $this->addFlash('notice', 'Kein Benutzer mit eingegebener E-Mail gefunden.');
            } else {
                $password = $sysUser->getPassword();
                $password = substr($password, 0, 5);
                $sysUser->setPassword(sha1($password));
                
                $em = $this->getDoctrine()->getManager();                
                $em->flush();

                $this->sendEmail('Neues Kennwort', $sysUser, 'email/password', $password);
                $this->addFlash('notice', 'Neues Kennwort wurde an die eingegebene E-Mail verschickt.');
                return $this->redirectToRoute('loginPage');
            }
        }
        
        return $this->render('login/passwordPage.html.twig', array( 
            'form' => $form->createView(),

        ));
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {
        $session = $request->getSession();
        $session->remove('user_id');

        $this->addFlash('notice', 'Benutzer hat sich erfolgreich abgemeldet');
        
        return $this->redirectToRoute('loginPage');
    }


    /**
     * @Route("/user/settings", name="userSettingsPage")
     */
    public function userSettingsAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $form = $this->createFormBuilder($sysUser)
            ->add('firstName', TextType::class, array('label' => 'Vorname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('lastName', TextType::class, array('label' => 'Nachname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('email', EmailType::class, array('label' => 'E-Mail', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('phoneNumber', TextType::class, array('label' => 'Tel. Nummer', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('password', PasswordType::class, array('label' => 'Kennwort', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $sysUser->setFirstName($form['firstName']->getData());
            $sysUser->setLastName($form['lastName']->getData());
            $sysUser->setEmail($form['email']->getData());
            $sysUser->setPhoneNumber($form['phoneNumber']->getData());
            $sysUser->setPassword(sha1($form['password']->getData()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($sysUser);
            $em->flush();

            $this->addFlash('notice', 'Einstellungen erfolgreich gespeichert');
        }
        
        return $this->render('default/userSettingsPage.html.twig', array(
            'title' => 'AOK | Einstellungen',
            'user' => $sysUser,
            'form' => $form->createView(),
        ));
    }
   

    private function checkLoggedUser(Request $request) {
        $session = $request->getSession();
        $userId = $session->get('user_id');

        if (!$userId) {
            return FALSE;
        } else {
            $sysUser = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findOneById($userId);
            return $sysUser;
        }
    }

    private function sendEmail($subject, $object, $viewPath, $options) 
    {
        /**
         * Get help about setting up mailing on localhost
         * http://www.developerfiles.com/how-to-send-emails-from-localhost-mac-os-x-el-capitan/
         * $ sudo postfix status  
         * postfix/postfix-script: the Postfix mail system is not running 
         * $ sudo postfix start  
         * postfix/postfix-script: starting the Postfix mail system  
         */
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('alsimfer@gmail.com')
            ->setTo($object->getEmail())
            ->setBody(
                $this->renderView(
                    $viewPath.'.html.twig',
                    array('object' => $object, 'options' => $options)
                ),
                'text/html'
            );

        $this->get('mailer')->send($message);
    }
}
