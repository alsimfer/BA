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

class MedCheckupController extends Controller
{
    /**
     * @Route("/med-checkups", name="medCheckupsPage")
     */
    public function medCheckupsAction(Request $request, array $options=null)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

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
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $medCheckup = new MedCheckup();
        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findAll();
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 4));
        
        $form = $this->createFormBuilder($medCheckup)
            ->add('type', ChoiceType::class, array(
                'label' => 'Typ', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices'  => array(
                    'Basischeck' => 'Basischeck',
                    'Zwischenuntersuchung' => 'Zwischenuntersuchung',
                    'OP-Untersuchung' => 'OP-Untersuchung',
                ),
                'placeholder' => 'Wählen Sie den Untersuchungstyp aus',
            ))
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
                'label' => 'Untersucher', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $sysUsers,
                'choice_label' => function($sysUser, $key, $index) {
                    return $sysUser->getFirstName().' '.$sysUser->getLastName();
                },                
                'placeholder' => 'Wählen Sie einen Untersucher aus',
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
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

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
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $medCheckup = $this->getDoctrine()->getRepository('AppBundle:MedCheckup')->findOneById($id);        
        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findAll();
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 4));
        
        $form = $this->createFormBuilder($medCheckup)
            ->add('type', ChoiceType::class, array(
                'label' => 'Typ', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices'  => array(
                    'Basischeck' => 'Basischeck',
                    'Zwischenuntersuchung' => 'Zwischenuntersuchung',
                    'OP-Untersuchung' => 'OP-Untersuchung',
                ),
                'placeholder' => 'Wählen Sie den Untersuchungstyp aus',
            ))
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
                'label' => 'Untersucher', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $sysUsers,
                'choice_label' => function($sysUser, $key, $index) {
                    return $sysUser->getFirstName().' '.$sysUser->getLastName();
                },                
                'placeholder' => 'Wählen Sie einen Untersucher aus',
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
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

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

}
