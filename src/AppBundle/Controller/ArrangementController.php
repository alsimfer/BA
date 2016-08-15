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

class ArrangementController extends Controller
{    
    /**
     * @Route("/arrangements", name="arrangementsPage")
     */
    public function arrangementsAction(Request $request)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $arrangements = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findAll();

        return $this->render('arrangement/arrangementsPage.html.twig', 
            array(
                'title' => 'AOK | Kurse',
                'user' => $sysUser,
                'arrangements' => $arrangements,
            )
        );
    }


    /**
     * @Route("/arrangements/create", name="createArrangementPage")
     */
    public function createArrangementAction(Request $request)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $arrangement = new Arrangement();
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 6));

        $form = $this->createFormBuilder($arrangement)
            ->add('name', TextType::class, array(
                'label' => 'Name', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('sysUser', ChoiceType::class, array(
                'label' => 'Kursleiter', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $sysUsers,
                'choice_label' => function($sysUser, $key, $index) {
                    return $sysUser->getFirstName().' '.$sysUser->getLastName();
                },                
                'placeholder' => 'Nicht bekannt',
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Beschreibung', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('maxParticipants', IntegerType::class, array(
                'label' => 'Max. Teilnehmer', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('dateTime', DateTimeType::class, [
                'label' => 'Datum und Uhrzeit',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
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
            $arrangement->setSysUser($form['sysUser']->getData());
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
            'user' => $sysUser
        ));
        
    }


    /**
     * @Route("/arrangements/edit/{id}", name="arrangementEditPage")
     */
    public function arrangementEditAction(Request $request, $id)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $arrangement = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findOneById($id);
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 6));

        $form = $this->createFormBuilder($arrangement)
            ->add('name', TextType::class, array(
                'label' => 'Name', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array(
                'label' => 'Beschreibung', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('sysUser', ChoiceType::class, array(
                'label' => 'Kursleiter', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $sysUsers,
                'choice_label' => function($sysUser, $key, $index) {
                    return $sysUser->getFirstName().' '.$sysUser->getLastName();
                },                
                'placeholder' => 'Nicht bekannt',
            ))
            ->add('maxParticipants', IntegerType::class, array(
                'label' => 'Max. Teilnehmer', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('dateTime', DateTimeType::class, [
                'label' => 'Datum und Uhrzeit',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
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
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/arrangements/info/{id}", name="arrangementInfoPage")
     */
    public function arrangementInfoAction(Request $request, $id)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

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
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

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

}
