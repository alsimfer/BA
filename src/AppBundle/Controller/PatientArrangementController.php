<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\Type\PatArrRefType;

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
        $patArrRefs = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findRelevantToUser($this->getUser());
    
        return $this->render('patientArrangement/patientArrangementPage.html.twig', 
            array(
                'title' => 'AOK | Kursverlauf',
                
                'patArrRefs' => $patArrRefs,
            )
        );
    }


    /**
     * @Route("/patient-arrangements/create", name="patientArrangementCreatePage")
     */
    public function patientArrangementCreateAction(Request $request)
    {        
        $patArrRef = new PatientArrangementReference();
        $before = clone($patArrRef);

        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findRelevantToUser($this->getUser());
        $arrangements = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findAll();
        
        $form = $this->createForm(PatArrRefType::class, $patArrRef, array(
                'validation_groups' => array('create', 'edit'),
                'patients' => $patients,
                'arrangements' => $arrangements,
            ));        
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $patArrRef->setPatient($form['patient']->getData());
            $patArrRef->setArrangement($form['arrangement']->getData());
            $patArrRef->setAttended($form['attended']->getData());
            $patArrRef->setComments($form['comments']->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($patArrRef);
            $em->flush();

            $util = $this->get('util');        
            $util->logAction($request, $patArrRef->getId(), $before, $patArrRef);

            $this->addFlash('notice', 'Ein neuer Kursverlauf erfolgreich gespeichert');

            return $this->redirectToRoute('patientArrangementPage');
        }
        
        return $this->render('patientArrangement/patientArrangementCreatePage.html.twig', array(
            'title' => 'AOK | Kursverlauf',
            
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/patient-arrangements/edit/{id}", name="patientArrangementAssessPage")
     */
    public function patientArrangementAssessAction(Request $request, $id)
    {        
        $patArrRef = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findOneById($id);
        $before = clone($patArrRef);

        $patients = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findPatientsByArrangementId($patArrRef->getArrangement()->getId());
        
        
dump($patients); die();
        $form = $this->createForm(PatArrRefType::class, $patArrRef, array(
                'validation_groups' => array('create', 'edit'),
                'patients' => $patients,
                'arrangements' => $arrangements,
            ));   

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $patArrRef->setPatient($form['patient']->getData());
            $patArrRef->setArrangement($form['arrangement']->getData());
            $patArrRef->setAttended($form['attended']->getData());
            $patArrRef->setComments($form['comments']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($patArrRef);
            $em->flush();

            $util = $this->get('util');        
            $util->logAction($request, $id, $before, $patArrRef);

            $this->addFlash('notice', 'Der Kursverlauf erfolgreich gespeichert');

            return $this->redirectToRoute('patientArrangementPage');
        }
        
        return $this->render('patientArrangement/patientArrangementEditPage.html.twig', array(
            'title' => 'AOK | Kursverlauf',
            
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/patient-arrangements/edit_legacy/{id}", name="patientArrangementEditLegacyPage")
     */
    public function patientArrangementEditLegacyAction(Request $request, $id)
    {        
        $patArrRef = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findOneById($id);
        $before = clone($patArrRef);

        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findRelevantToUser($this->getUser());
        $arrangements = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findAll();
        

        $form = $this->createForm(PatArrRefType::class, $patArrRef, array(
                'validation_groups' => array('create', 'edit'),
                'patients' => $patients,
                'arrangements' => $arrangements,
            ));   

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $patArrRef->setPatient($form['patient']->getData());
            $patArrRef->setArrangement($form['arrangement']->getData());
            $patArrRef->setAttended($form['attended']->getData());
            $patArrRef->setComments($form['comments']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($patArrRef);
            $em->flush();

            $util = $this->get('util');        
            $util->logAction($request, $id, $before, $patArrRef);

            $this->addFlash('notice', 'Der Kursverlauf erfolgreich gespeichert');

            return $this->redirectToRoute('patientArrangementPage');
        }
        
        return $this->render('patientArrangement/patientArrangementEditPage.html.twig', array(
            'title' => 'AOK | Kursverlauf',
            
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/patient-arrangements/info/{id}", name="patientArrangementInfoPage")
     */
    public function patientArrangementInfoAction(Request $request, $id)
    {        
        $patArrRef = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findOneById($id);
        $arrangement = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findOneById($patArrRef->getArrangement()->getId());
        $patient = $this->getDoctrine()->getRepository('AppBundle:Patient')->findOneById($patArrRef->getPatient()->getId());;
    
        return $this->render('patientArrangement/patientArrangementInfoPage.html.twig', 
            array(
                'title' => 'AOK | Kursverlauf',
                
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
        $patArrRef = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findOneById($id);
        $before = clone($patArrRef);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($patArrRef);
        $em->flush();

        $util = $this->get('util');        
        $util->logAction($request, $id, $before, $patArrRef);
        
        $this->addFlash('notice', 'Kursverlauf erfolgreich gelöscht');
        
        return $this->redirectToRoute('patientArrangementPage');        

    }

}
