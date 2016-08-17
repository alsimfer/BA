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

class PatientArrangementController extends Controller
{
    /**
     * @Route("/patient-arrangements", name="patientArrangementPage")
     */
    public function patientArrangementAction(Request $request)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patArrRefs = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findBy(array(), array('id' => 'DESC'), 1000, 0);
    
        return $this->render('patientArrangement/patientArrangementPage.html.twig', 
            array(
                'title' => 'AOK | Kursverlauf',
                'user' => $sysUser,
                'patArrRefs' => $patArrRefs,
            )
        );
    }


    /**
     * @Route("/patient-arrangements/create", name="patientArrangementCreatePage")
     */
    public function patientArrangementCreateAction(Request $request)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);
        
        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patArrRef = new PatientArrangementReference();
        $before = clone($patArrRef);

        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findRelevantToUser($request->attributes->get('user'));
        $arrangements = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findAll();
        
        $form = $this->createFormBuilder($patArrRef, array('validation_groups' => array('registration', 'definedRef'),))
            ->add('patient', ChoiceType::class, array(
                'label' => 'Patient', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $patients,
                'choice_label' => function($patient, $key, $index) {
                    $birthDate = '';
                    if (is_null($patient->getBirthDate()) === FALSE) {
                        $birthDate = $patient->getBirthDate()->format('d.m.Y');
                    }
                    
                    return $patient->getFirstName().' '.$patient->getLastName().', '.$birthDate;
                },  
                'placeholder' => 'Wählen Sie einen Patient aus',              
            ))
            ->add('arrangement', ChoiceType::class, array(
                'label' => 'Kurs', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $arrangements,
                'choice_label' => function($arrangement, $key, $index) {
                    return $arrangement->getName().' am '.$arrangement->getDateTime()->format('d.m.Y H:i');
                },  
                'placeholder' => 'Wählen Sie einen Kurs aus',              
            ))
            ->add('attended', ChoiceType::class, array(
                'label' => 'Besucht', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices'  => array(
                    'Ja' => 2,
                    'Nein' => 1,
                ),
                'placeholder' => 'Besucht?',
            ))
            ->add('comments', TextareaType::class, array(
                'label' => 'Commentare', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $patArrRef->setPatient($form['patient']->getData());
            $patArrRef->setArrangement($form['arrangement']->getData());
            $patArrRef->setAttended($form['attended']->getData());
            $patArrRef->setComments($form['comments']->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($patArrRef);
            $em->flush();

            $util->logAction($request, $patArrRef->getId(), $before, $patArrRef);

            $this->addFlash('notice', 'Ein neuer Kursverlauf erfolgreich gespeichert');

            return $this->redirectToRoute('patientArrangementPage');
        }
        
        return $this->render('patientArrangement/patientArrangementCreatePage.html.twig', array(
            'title' => 'AOK | Kursverlauf',
            'user' => $sysUser,
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/patient-arrangements/edit/{id}", name="patientArrangementEditPage")
     */
    public function patientArrangementEditAction(Request $request, $id)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patArrRef = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findOneById($id);
        $before = clone($patArrRef);

        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findRelevantToUser($request->attributes->get('user'));
        $arrangements = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findAll();
        
        $form = $this->createFormBuilder($patArrRef, array('validation_groups' => array('registration'),))
            ->add('patient', ChoiceType::class, array(
                'label' => 'Patient', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $patients,
                'choice_label' => function($patient, $key, $index) {
                    $birthDate = '';
                    if (is_null($patient->getBirthDate()) === FALSE) {
                        $birthDate = $patient->getBirthDate()->format('d.m.Y');
                    }
                    
                    return $patient->getFirstName().' '.$patient->getLastName().', '.$birthDate;
                },  
                'placeholder' => 'Wählen Sie einen Patient aus',              
            ))
            ->add('arrangement', ChoiceType::class, array(
                'label' => 'Kurs', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $arrangements,
                'choice_label' => function($arrangement, $key, $index) {
                    return $arrangement->getName().' am '.$arrangement->getDateTime()->format('d.m.Y H:i');
                },  
                'placeholder' => 'Wählen Sie einen Kurs aus',              
            ))
            ->add('attended', ChoiceType::class, array(
                'label' => 'Besucht', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices'  => array(
                    'Ja' => 2,
                    'Nein' => 1,
                ),
                'placeholder' => 'Besucht?',
            ))
            ->add('comments', TextareaType::class, array(
                'label' => 'Commentare', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $patArrRef->setPatient($form['patient']->getData());
            $patArrRef->setArrangement($form['arrangement']->getData());
            $patArrRef->setAttended($form['attended']->getData());
            $patArrRef->setComments($form['comments']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($patArrRef);
            $em->flush();

            $util->logAction($request, $id, $before, $patArrRef);

            $this->addFlash('notice', 'Der Kursverlauf erfolgreich gespeichert');

            return $this->redirectToRoute('patientArrangementPage');
        }
        
        return $this->render('patientArrangement/patientArrangementEditPage.html.twig', array(
            'title' => 'AOK | Kursverlauf',
            'user' => $sysUser,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/patient-arrangements/info/{id}", name="patientArrangementInfoPage")
     */
    public function patientArrangementInfoAction(Request $request, $id)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patArrRef = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findOneById($id);
        $arrangement = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findOneById($patArrRef->getArrangement()->getId());
        $patient = $this->getDoctrine()->getRepository('AppBundle:Patient')->findOneById($patArrRef->getPatient()->getId());;
    
        return $this->render('patientArrangement/patientArrangementInfoPage.html.twig', 
            array(
                'title' => 'AOK | Kursverlauf',
                'user' => $sysUser,
                'arrangement' => $arrangement,
                'patient' => $patient,
                'patArrRef' => $patArrRef,
            )
        );
    }

   /**
     * @Route("/patient-arrangements/delete/{id}", name="patientArrangementDeletePage")
     */
    public function patientArrangementDeleteAction(Request $request, $id)
    {
        $util = $this->get('util');
        $sysUser = $util->checkLoggedUser($request);

        if (!$sysUser) {
            return $this->redirectToRoute('loginPage');
        }

        $patArrRef = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findOneById($id);
        $before = clone($patArrRef);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($patArrRef);
        $em->flush();

        $util->logAction($request, $id, $before, $patArrRef);
        
        $this->addFlash('notice', 'Kursverlauf erfolgreich gelöscht');
        
        return $this->redirectToRoute('patientArrangementPage');        

    }

}
