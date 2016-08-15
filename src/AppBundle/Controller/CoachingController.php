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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use AppBundle\Entity\SysUser;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Arrangement;
use AppBundle\Entity\MedCheckup;
use AppBundle\Entity\PatientArrangementReference;
use AppBundle\Entity\Coaching;
use AppBundle\Controller\Util;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CoachingController extends Controller
{
    /**
     * @Route("/coachings", name="coachingsPage")
     */
    public function coachingsAction(Request $request, array $options=null)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $coachings = $this->getDoctrine()->getRepository('AppBundle:Coaching')->findAll();

        return $this->render('coaching/coachingsPage.html.twig', 
            array(
                'title' => 'AOK | Coachings',
                'user' => $sysUser,
                'coachings' => $coachings,
            )
        );
    }


    /**
     * @Route("/coachings/create", name="coachingCreatePage")
     */
    public function coachingCreateAction(Request $request)
    {
        $util = $this->get('util');
        $currentUser = $util->checkLoggedUser($request);

        if (!$currentUser) {
            return $this->redirectToRoute('loginPage');
        }

        $coaching = new Coaching();
        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findAll();
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 5));
        
        $form = $this->createFormBuilder($coaching)
            ->add('patient', ChoiceType::class, array(
                'label' => 'Patient', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $patients,
                'choice_label' => function($patient, $key, $index) {
                    return $patient->getFirstName().' '.$patient->getLastName();
                },                
                'placeholder' => 'Wählen Sie einen Patient aus',
            ))
            ->add('sysUser', ChoiceType::class, array(
                'label' => 'Coach', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $sysUsers,
                'choice_label' => function($sysUser, $key, $index) {
                    return $sysUser->getFirstName().' '.$sysUser->getLastName();
                },                
                'placeholder' => 'Wählen Sie einen Coach aus',
            ))
            ->add('dateAndTime', DateTimeType::class, [
                'label' => 'Datum',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy HH:mm',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('weekGoal', TextareaType::class, array(
                'label' => 'Ziel',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $coaching->setPatient($form['patient']->getData());
            $coaching->setSysUser($form['sysUser']->getData());
            $coaching->setDateAndTime($form['dateAndTime']->getData());
            $coaching->setWeekGoal($form['weekGoal']->getData());
            
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($coaching);
            $em->flush();

            $this->addFlash('notice', 'Ein Coaching erfolgreich hinzugefügt');
            
            return $this->redirectToRoute('coachingsPage');
        }
        
        return $this->render('coaching/coachingCreatePage.html.twig', array(
            'title' => 'AOK | Coachings | Erstellen',
            'form' => $form->createView(),
            'user' => $currentUser
        ));
        
    }


    /**
     * @Route("/coachings/edit/{id}", name="coachingEditPage")
     */
    public function coachingEditAction(Request $request, $id)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $coaching = $this->getDoctrine()->getRepository('AppBundle:Coaching')->findOneById($id);        
        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findAll();
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 5));
        
        $form = $this->createFormBuilder($coaching)
            ->add('patient', ChoiceType::class, array(
                'label' => 'Patient', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $patients,
                'choice_label' => function($patient, $key, $index) {
                    return $patient->getFirstName().' '.$patient->getLastName();
                },                
                'placeholder' => 'Wählen Sie einen Patient aus',
            ))
            ->add('sysUser', ChoiceType::class, array(
                'label' => 'Coach', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $sysUsers,
                'choice_label' => function($sysUser, $key, $index) {
                    return $sysUser->getFirstName().' '.$sysUser->getLastName();
                },                
                'placeholder' => 'Wählen Sie einen Coach aus',
            ))
            ->add('dateAndTime', DateTimeType::class, [
                'label' => 'Datum',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy HH:mm',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('weekGoal', TextareaType::class, array(
                'label' => 'Ziel',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $coaching->setPatient($form['patient']->getData());
            $coaching->setSysUser($form['sysUser']->getData());
            $coaching->setDateAndTime($form['dateAndTime']->getData());
            $coaching->setWeekGoal($form['weekGoal']->getData());
            
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($coaching);
            $em->flush();

            $this->addFlash('notice', 'Das Coaching erfolgreich gespeichert');
            
            return $this->redirectToRoute('coachingsPage');
        }
        
        return $this->render('coaching/coachingEditPage.html.twig', array(
            'title' => 'AOK | Coachings | Bearbeiten',
            'form' => $form->createView(),
            'user' => $sysUser
        ));
        
    }
    

    /**
     * @Route("/coachings/info/{id}", name="coachingInfoPage")
     */
    public function coachingInfoAction(Request $request, $id)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $coaching = $this->getDoctrine()->getRepository('AppBundle:Coaching')->findOneById($id);

        return $this->render('coaching/coachingInfoPage.html.twig', 
            array(
                'title' => 'AOK | Coachings | Info',
                'user' => $sysUser,
                'coaching' => $coaching,
            )
        );
    }


    /**
     * @Route("/coachings/delete/{id}", name="coachingDeletePage")
     */
    public function coachingDeleteAction(Request $request, $id)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $coaching = $this->getDoctrine()->getRepository('AppBundle:Coaching')->findOneById($id);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($coaching);
        $em->flush();

        $this->addFlash('notice', 'Coaching erfolgreich gelöscht');
        
        return $this->redirectToRoute('coachingsPage');
        
    }

}
