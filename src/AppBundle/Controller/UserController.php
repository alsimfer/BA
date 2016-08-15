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

class UserController extends Controller
{    
    /**
     * @Route("/users", name="usersPage")
     */
    public function usersAction(Request $request)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $users = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findAll();
        return $this->render('user/usersPage.html.twig', 
            array(
                'title' => 'AOK | Benutzer',
                'user' => $sysUser,
                'users' => $users,
                
            )
        );
    }
    /**
     * @Route("/users/create", name="createUserPage")
     */
    public function createUserAction(Request $request)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }
        $user = new SysUser();

        // Prepare selectField with userGroups for the form.
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery('select u from AppBundle\Entity\UserGroup u where u.id >= 2');        
        $userGroups = $q->getResult();

        $hospitals = $this->getDoctrine()->getRepository('AppBundle:Hospital')->findAll();

        $form = $this->createFormBuilder($user, array('validation_groups' => array('createUserAction'),))
            ->add('firstName', TextType::class, array(
                'label' => 'Vorname', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'empty_data' => '',
                'attr' => array('class' => 'form-control')))
            ->add('lastName', TextType::class, array(
                'label' => 'Nachname', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'empty_data' => '',
                'attr' => array('class' => 'form-control')))
            ->add('email', EmailType::class, array(
                'label' => 'E-Mail', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('phoneNumber', TextType::class, array(
                'label' => 'Tel. Nummer', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'empty_data' => '',
                'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array(
                'label' => 'Adresse', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'empty_data' => '',
                'attr' => array('class' => 'form-control')))
            ->add('sex', ChoiceType::class, array(
                'label' => 'Geschlecht', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),                
                'choices'  => array(
                    'männlich' => 'männlich',
                    'weiblich' => 'weiblich',
                ),
                'placeholder' => 'Wählen Sie ein Geschlecht aus',
            ))
            ->add('password', PasswordType::class, array(
                'label' => 'Kennwort', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('userGroup', ChoiceType::class, array(
                'label' => 'Benutzergruppe', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $userGroups,
                'choice_label' => function($userGroup, $key, $index) {
                    return $userGroup->getName().': '.$userGroup->getDescription();
                },                
            ))
            ->add('hospital', ChoiceType::class, array(
                'label' => 'Krankenhaus', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $hospitals,
                'choice_label' => function($hospital, $key, $index) {
                    return $hospital->getName().': '.$hospital->getDescription();
                },                
                'placeholder' => 'Keine Zuweisung: Alle Patienten sehbar',
            ))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        $form->handleRequest($request);
  
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFirstName($form['firstName']->getData());
            $user->setLastName($form['lastName']->getData());
            $user->setEmail($form['email']->getData());
            $user->setPhoneNumber($form['phoneNumber']->getData());
            $user->setSex($form['sex']->getData());
            $user->setAddress($form['address']->getData());
            $user->setUserGroup($form['userGroup']->getData());
            $user->setHospital($form['hospital']->getData());
            $user->setPassword(sha1($form['password']->getData()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', 'Benutzer erfolgreich hinzugefügt');

#            $this->sendEmail('Registrierung erfolgreich abgeschlossen', $user, 'email/registration');
            return $this->redirectToRoute('usersPage');
        }
        
        return $this->render('user/userCreatePage.html.twig', array(
            'title' => 'AOK | Benutzer',
            'user' => $sysUser,
            'form' => $form->createView(),
            
        ));
    }

    
    /**
     * @Route("/users/info/{id}", name="userInfoPage")
     */
    public function userInfoAction(Request $request, $id)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findOneById($id);
        
        return $this->render('user/userInfoPage.html.twig', 
            array(
                'title' => 'AOK | Benutzer | Info',
                'user' => $sysUser,
                'sysUsers' => $sysUsers
            )
        );
    }


    /**
     * @Route("/users/edit/{id}", name="userEditPage")
     */
    public function userEditAction(Request $request, $id)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }
        $user = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findOneById($id);
        
        // Prepare selectField with userGroups for the form.
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery('select u from AppBundle\Entity\UserGroup u where u.id >= 2');        
        $userGroups = $q->getResult();
        
        $hospitals = $this->getDoctrine()->getRepository('AppBundle:Hospital')->findAll();

        $form = $this->createFormBuilder($user, array('validation_groups' => array('registration'),))
            ->add('firstName', TextType::class, array(
                'label' => 'Vorname', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('lastName', TextType::class, array(
                'label' => 'Nachname', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('email', EmailType::class, array(
                'label' => 'E-Mail', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('phoneNumber', TextType::class, array(
                'label' => 'Tel. Nummer', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array(
                'label' => 'Adresse', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'empty_data' => '',
                'attr' => array('class' => 'form-control')))
            ->add('sex', ChoiceType::class, array(
                'label' => 'Geschlecht', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),                
                'choices'  => array(
                    'männlich' => 'männlich',
                    'weiblich' => 'weiblich',
                ),
                'placeholder' => 'Wählen Sie ein Geschlecht aus',
            ))
            ->add('userGroup', ChoiceType::class, array(
                'label' => 'Benutzergruppe', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $userGroups,
                'choice_label' => function($userGroup, $key, $index) {
                    return $userGroup->getName().': '.$userGroup->getDescription();
                },                
            ))
            ->add('hospital', ChoiceType::class, array(
                'label' => 'Krankenhaus', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $hospitals,
                'choice_label' => function($hospital, $key, $index) {
                    return $hospital->getName().': '.$hospital->getDescription();
                },                
                'placeholder' => 'Keine Zuweisung: Alle Patienten sehbar',
            ))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFirstName($form['firstName']->getData());
            $user->setLastName($form['lastName']->getData());
            $user->setEmail($form['email']->getData());
            $user->setPhoneNumber($form['phoneNumber']->getData());
            $user->setSex($form['sex']->getData());
            $user->setAddress($form['address']->getData());
            $user->setUserGroup($form['userGroup']->getData());
            $user->setHospital($form['hospital']->getData());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', 'Benutzer erfolgreich gespeichert');
            return $this->redirectToRoute('usersPage');
        }
        
        return $this->render('user/userEditPage.html.twig', array(
            'title' => 'AOK | Benutzer',
            'user' => $sysUser,
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/users/delete/{id}", name="userDeletePage")
     */
    public function userDeleteAction(Request $request, $id)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

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
     * @Route("/user/settings", name="userSettingsPage")
     */
    public function userSettingsAction(Request $request)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $form = $this->createFormBuilder($sysUser)
            ->add('firstName', TextType::class, array(
                'label' => 'Vorname', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('lastName', TextType::class, array(
                'label' => 'Nachname', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('email', EmailType::class, array(
                'label' => 'E-Mail', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('phoneNumber', TextType::class, array(
                'label' => 'Tel. Nummer', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array(
                'label' => 'Adresse', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'empty_data' => '',
                'attr' => array('class' => 'form-control')))
            ->add('sex', ChoiceType::class, array(
                'label' => 'Geschlecht', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),                
                'choices'  => array(
                    'männlich' => 'männlich',
                    'weiblich' => 'weiblich',
                ),
                'placeholder' => 'Wählen Sie ein Geschlecht aus',
            ))
            ->add('password', PasswordType::class, array(
                'label' => 'Kennwort', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))                    
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $sysUser->setFirstName($form['firstName']->getData());
            $sysUser->setLastName($form['lastName']->getData());
            $sysUser->setEmail($form['email']->getData());
            $sysUser->setPhoneNumber($form['phoneNumber']->getData());
            $sysUser->setSex($form['sex']->getData());
            $sysUser->setAddress($form['address']->getData());
            $sysUser->setPassword(sha1($form['password']->getData()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($sysUser);
            $em->flush();
            $this->addFlash('notice', 'Einstellungen erfolgreich gespeichert');
        }
        
        // same page as new User.
        return $this->render('user/userSettingsPage.html.twig', array(
            'title' => 'AOK | Einstellungen',
            'user' => $sysUser,
            'form' => $form->createView(),
        ));
    }

}
