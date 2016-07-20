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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use AppBundle\Entity\SysUser;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Arrangement;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homePage")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('patientsPage');        
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

        return $this->render('default/patientsPage.html.twig', 
            array(
                'title' => 'AOK | Patienten',
                'user' => $sysUser,
                'patients' => $patients,
                'url' => 'patients',
                'buttonLabel' => 'Patient',
                'pageHeader' => 'Übersicht aller Patienten'
            )
        );
    }


    /**
     * @Route("/patients/create", name="createPatientPage")
     */
    public function createPatientAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patient = new Patient();

        $form = $this->createFormBuilder($patient, array('validation_groups' => array('registration'),))
            ->add('firstName', TextType::class, array('label' => 'Vorname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('lastName', TextType::class, array('label' => 'Nachname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('email', TextType::class, array('label' => 'E-Mail', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('phoneNumber', TextType::class, array('label' => 'Tel. Nummer', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $patient->setFirstName($form['firstName']->getData());
            $patient->setLastName($form['lastName']->getData());
            $patient->setEmail($form['email']->getData());
            $patient->setPhoneNumber($form['phoneNumber']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();

            $this->addFlash('notice', 'Ein neuer Patient erfolgreich hinzugefügt');
            
            return $this->redirectToRoute('patientsPage');
        }
        
        return $this->render('default/editObjectPage.html.twig', array(
            'title' => 'AOK | Patienten',
            'form' => $form->createView(),
            'user' => $sysUser,
            'pageHeader' => 'Patient einfügen',
            'validation_groups' => array('registration')
        ));
        
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
        
        $form = $this->createFormBuilder($patient)
            ->add('firstName', TextType::class, array('label' => 'Vorname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('lastName', TextType::class, array('label' => 'Nachname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('email', TextType::class, array('label' => 'E-Mail', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('phoneNumber', TextType::class, array('label' => 'Tel. Nummer', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $patient->setFirstName($form['firstName']->getData());
            $patient->setLastName($form['lastName']->getData());
            $patient->setEmail($form['email']->getData());
            $patient->setPhoneNumber($form['phoneNumber']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();

            $this->addFlash('notice', 'Patient erfolgreich gespeichert');
            
            return $this->redirectToRoute('patientsPage');
        }
        
        return $this->render('default/editObjectPage.html.twig', array(
            'title' => 'AOK | Patienten',
            'form' => $form->createView(),
            'user' => $sysUser,
            'pageHeader' => 'Patient bearbeiten'

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
     * @Route("/arrangements", name="arrangementsPage")
     */
    public function arrangementsAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $arrangements = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findAll();

        return $this->render('default/arrangementsPage.html.twig', 
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
            ->add('dateTime', DateTimeType::class, array('label' => 'Datum', 'attr' => array('style' => 'margin-bottom: 15px')))
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
        
        return $this->render('default/editObjectPage.html.twig', array(
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
            ->add('description', TextType::class, array('label' => 'Beschreibung', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('maxParticipants', IntegerType::class, array('label' => 'Max. Teilnehmer', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('dateTime', DatetimeType::class, array('label' => 'Datum', 'attr' => array('style' => 'margin-bottom: 15px')))        
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $arrangement->setName($form['name']->getData());
            $arrangement->setDescription($form['description']->getData());
            $arrangement->setMaxParticipants($form['maxParticipants']->getData());
            $arrangement->setDateTime($form['dateTime']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($arrangement);
            $em->flush();

            $this->addFlash('notice', 'Kurs erfolgreich gespeichert');

            return $this->redirectToRoute('arrangementsPage');
        }
        
        return $this->render('default/editObjectPage.html.twig', array(
            'title' => 'AOK | Arrangements',
            'user' => $sysUser,
            'form' => $form->createView(),
            'pageHeader' => 'Kurs bearbeiten'
        ));
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
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
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
