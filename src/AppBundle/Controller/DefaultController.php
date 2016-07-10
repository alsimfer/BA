<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\SysUser;

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
        $session = $request->getSession();
        $userId = $session->get('user_id');
        
        if (!$userId) {
            return $this->redirectToRoute('loginPage');
        } else {
            $sysUser = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findOneById($userId);
            $userFirstName = $sysUser->getFirstName();
            return $this->render('default/mainPage.html.twig', 
                array(
                    'title' => 'AOK | Patienten',
                    'userFirstName' => $userFirstName
                )
            );
        }
        
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
        
        return $this->render('default/loginForm.html.twig', array( 
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
        $session = $request->getSession();
        $userId = $session->get('user_id');
        $sysUser = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findOneById($userId);        

        $form = $this->createFormBuilder($sysUser)
            ->add('firstName', TextType::class, array('label' => 'Vorname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('lastName', TextType::class, array('label' => 'Nachname', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('email', TextType::class, array('label' => 'E-Mail', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('password', PasswordType::class, array('label' => 'Kennwort', 'attr' => array('class' => 'form-control','style' => 'margin-bottom: 15px')))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $sysUser->setFirstName($form['firstName']->getData());
            $sysUser->setLastName($form['lastName']->getData());
            $sysUser->setEmail($form['email']->getData());
            $sysUser->setPassword(sha1($form['password']->getData()));
            
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('notice', 'Einstellungen erfolgreich gespeichert');
        }
        
        $userFirstName = $sysUser->getFirstName();
        
        return $this->render('default/userSettingsPage.html.twig', array(
            'title' => 'AOK | Einstellungen',
            'userFirstName' => $userFirstName,
            'form' => $form->createView(),
        ));
    }
   
}
