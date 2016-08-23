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
use AppBundle\Controller\Util;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class LoginController extends Controller
{    
    /**
     * @Route("/login", name="loginPage")
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        $session->remove('user_id');
        
        $sysUser = new SysUser();
        $form = $this->createFormBuilder($sysUser, array('validation_groups' => array('login'),))
            ->add('email', TextType::class, array(
                'label' => 'E-Mail', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('password', PasswordType::class, array(
                'label' => 'Kennwort', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false,
                'attr' => array('class' => 'form-control')))
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
                return $this->redirectToRoute('indexPage');
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
                $this->addFlash('notice', 'Kein Benutzer mit eingegebener E-Mail gefunden');
            } else {
                $password = $sysUser->getPassword();
                $password = substr($password, 0, 5);
                $sysUser->setPassword(sha1($password));
                
                $em = $this->getDoctrine()->getManager();                
                $em->flush();
                $this->sendEmail('Neues Kennwort', $sysUser, 'email/password', $password);
                $this->addFlash('notice', 'Neues Kennwort wurde an die eingegebene E-Mail verschickt');
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

}
