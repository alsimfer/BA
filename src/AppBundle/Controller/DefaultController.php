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
                'patients' => $patients
            )
        );
    }

    /**
     * @Route("/create-patient", name="createPatientPage")
     */
    public function createPatientAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patient = new Patient();

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

            $this->addFlash('notice', 'Ein neuer Patient erfolgreich hinzugefügt');
            
            return $this->redirectToRoute('patientsPage');
        }
        
        return $this->render('default/createPatientPage.html.twig', array(
            'title' => 'AOK | Patienten',
            'form' => $form->createView(),
            'user' => $sysUser
        ));
        
    }
    
    /**
     * @Route("/login", name="loginPage")
     */
    public function loginAction(Request $request)
    {
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
        
        return $this->render('default/loginPage.html.twig', array( 
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
     * @Route("/user-settings", name="userSettingsPage")
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
   
    /**
     * @Route("/arrangements", name="arrangementsPage")
     */
    public function arrangementsAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        return $this->render('default/arrangementsPage.html.twig', 
            array(
                'title' => 'AOK | Kurse',
                'user' => $sysUser
            )
        );
    }

    /**
     * @Route("/create-arrangement", name="createArrangementPage")
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
            ->add('maxParticipants', NumberType::class, array('label' => 'Max. Teilnehmer', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
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
        
        return $this->render('default/createArrangementPage.html.twig', array(
            'title' => 'AOK | Kurse',
            'form' => $form->createView(),
            'user' => $sysUser
        ));
        
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

        return $this->render('default/usersPage.html.twig', 
            array(
                'title' => 'AOK | Benutzer',
                'user' => $sysUser
            )
        );
    }

    /**
     * @Route("/create-user", name="createUserPage")
     */
    public function createUserAction(Request $request)
    {
        $sysUser = $this->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $user = new SysUser();

        $form = $this->createFormBuilder($user)
            ->add('firstName', TextType::class, array('label' => 'Vorname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('lastName', TextType::class, array('label' => 'Nachname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('email', EmailType::class, array('label' => 'E-Mail', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('phoneNumber', TextType::class, array('label' => 'Tel. Nummer', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('password', PasswordType::class, array('label' => 'Kennwort', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
//            ->add('userGroup', TextType::class, array('label' => 'Kennwort', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFirstName($form['firstName']->getData());
            $user->setLastName($form['lastName']->getData());
            $user->setEmail($form['email']->getData());
            $user->setPhoneNumber($form['phoneNumber']->getData());
            $user->setPassword(sha1($form['password']->getData()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'Benutzer erfolgreich hinzugefügt');

            return $this->redirectToRoute('usersPage');
        }
        
        return $this->render('default/createUserPage.html.twig', array(
            'title' => 'AOK | Benutzer',
            'user' => $sysUser,
            'form' => $form->createView(),
        ));
    }

    // /**                                                                                   
    //  * @Route("/patients/ajax", name="patientsAjax")
    //  * @Method({"GET", "POST"})
    //  */
    // public function patientsAjaxAction(Request $request)    
    // {
    //     if ($request->isXMLHttpRequest()) {         
    //         return new JsonResponse(array('data' => 'this is a json response'));
    //     }

    //     return new Response('This is not ajax!', 400);
    // }

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
}
