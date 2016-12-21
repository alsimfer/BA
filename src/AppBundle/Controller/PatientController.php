<?php

namespace AppBundle\Controller;

use AppBundle\Controller\TokenAuthenticatedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\Type\PatientType;

use AppBundle\Entity\SysUser;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Arrangement;
use AppBundle\Entity\MedCheckup;
use AppBundle\Entity\PatientArrangementReference;
use AppBundle\Controller\Util;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class PatientController extends Controller
{
    
    /**
     * @Route("/patients", name="patientsPage")
     */
    public function patientsAction(Request $request)
    {        
        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findRelevantToUser($this->getUser());

        return $this->render('patient/patientsPage.html.twig', 
            array(
                'title' => 'AOK | Patienten',
                'patients' => $patients,
            )
        );
    }

    /**
     * @Route("/patients/create", name="patientCreatePage")
     */
    public function patientCreateAction(Request $request)
    {        
        $patient = new Patient();
        // Snapshot for logging
        $before = clone($patient);

        $hospitals = $this->getDoctrine()->getRepository('AppBundle:Hospital')->findAll();
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 5));

        $form = $this->createForm(PatientType::class, $patient, array(
                'validation_groups' => array('create'),
                'hospitals' => $hospitals,
                'sysUsers' => $sysUsers,
            ));
        
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $patient->setFirstName($form['firstName']->getData());
            $patient->setLastName($form['lastName']->getData());
            $patient->setBirthDate($form['birthDate']->getData());
            $patient->setSex($form['sex']->getData());        
            $patient->setEmail($form['email']->getData());
            $patient->setPhoneNumber($form['phoneNumber']->getData());
            $patient->setAddress($form['address']->getData());
            $patient->setHospital($form['hospital']->getData());
            $patient->setSysUser($form['sysUser']->getData());

            $patient->setKrankenversicherungsart($form['krankenversicherungsart']->getData());
            $patient->setKrankenkassennummer($form['krankenkassennummer']->getData());
            $patient->setKrankenkasse($form['krankenkasse']->getData());
            $patient->setKassennameZurBedruckung($form['kassennameZurBedruckung']->getData());
            $patient->setVersichertennummer($form['versichertennummer']->getData());
            $patient->setEgkVersichertenNr($form['egkVersichertenNr']->getData());
            $patient->setKostentraegerabrechnungsbereich($form['kostentraegerabrechnungsbereich']->getData());
            $patient->setKvBereich($form['kvBereich']->getData());
            $patient->setAbrechnungsvknr($form['abrechnungsvknr']->getData());
            $patient->setSonstige($form['sonstige']->getData());
            $patient->setVersichertenartmfr($form['versichertenartmfr']->getData());
            $patient->setVersichertenstatuskvk($form['versichertenstatuskvk']->getData());
            $patient->setStatusergaenzung($form['statusergaenzung']->getData());
            $patient->setValidTill($form['validTill']->getData());
            $patient->setAbrechnungsform($form['abrechnungsform']->getData());
            $patient->setNachsorge($form['nachsorge']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);
            $em->flush();

            $util = $this->get('util');
            $util->logAction($request, $patient->getId(), $before, $patient);
            
            $this->addFlash('notice', 'Ein neuer Patient erfolgreich hinzugefügt');
            
            return $this->redirectToRoute('patientsPage');
        }
        
        $return = $this->render('patient/patientCreatePage.html.twig', array(
            'title' => 'AOK | Patienten | Erstellen',
            'form' => $form->createView(),
        ));

        return $return;
    }
        
    /**
     * @Route("/patients/edit/{id}", name="patientEditPage")
     */
    public function patientEditAction(Request $request, $id)
    {        
        $patient = $this->getDoctrine()->getRepository('AppBundle:Patient')->findOneById($id);
        // Snapshot for logging
        $before = clone($patient);
        
        $hospitals = $this->getDoctrine()->getRepository('AppBundle:Hospital')->findAll();
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 5));   

        $form = $this->createForm(PatientType::class, $patient, array(
                'validation_groups' => array('edit'),
                'hospitals' => $hospitals,
                'sysUsers' => $sysUsers,
            ));        
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $patient->setFirstName($form['firstName']->getData());
            $patient->setLastName($form['lastName']->getData());
            $patient->setBirthDate($form['birthDate']->getData());
            $patient->setSex($form['sex']->getData());        
            $patient->setEmail($form['email']->getData());
            $patient->setPhoneNumber($form['phoneNumber']->getData());
            $patient->setAddress($form['address']->getData());
            $patient->setHospital($form['hospital']->getData());
            $patient->setSysUser($form['sysUser']->getData());

            $patient->setKrankenversicherungsart($form['krankenversicherungsart']->getData());
            $patient->setKrankenkassennummer($form['krankenkassennummer']->getData());
            $patient->setKrankenkasse($form['krankenkasse']->getData());
            $patient->setKassennameZurBedruckung($form['kassennameZurBedruckung']->getData());
            $patient->setVersichertennummer($form['versichertennummer']->getData());
            $patient->setEgkVersichertenNr($form['egkVersichertenNr']->getData());
            $patient->setKostentraegerabrechnungsbereich($form['kostentraegerabrechnungsbereich']->getData());
            $patient->setKvBereich($form['kvBereich']->getData());
            $patient->setAbrechnungsvknr($form['abrechnungsvknr']->getData());
            $patient->setSonstige($form['sonstige']->getData());
            $patient->setVersichertenartmfr($form['versichertenartmfr']->getData());
            $patient->setVersichertenstatuskvk($form['versichertenstatuskvk']->getData());
            $patient->setStatusergaenzung($form['statusergaenzung']->getData());
            $patient->setValidTill($form['validTill']->getData());
            $patient->setAbrechnungsform($form['abrechnungsform']->getData());
            $patient->setNachsorge($form['nachsorge']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($patient);            
            $em->flush();

            // Log differences.
            $util = $this->get('util');
            $util->logAction($request, $id, $before, $patient);
            
            $this->addFlash('notice', 'Patient erfolgreich gespeichert');
            
            return $this->redirectToRoute('patientsPage');
        }
        
        return $this->render('patient/patientEditPage.html.twig', array(
            'title' => 'AOK | Patienten | Bearbeiten',
            'form' => $form->createView(),
        ));
        
    }

    /**
     * @Route("/patients/info/{id}", name="patientInfoPage")
     */
    public function patientInfoAction(Request $request, $id)
    {
        $patient = $this->getDoctrine()->getRepository('AppBundle:Patient')->findOneById($id);        

        $patArrRefs = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findByPatient($id);
        $arrangements = array();
        foreach ($patArrRefs as $key => $value) {
            array_push($arrangements, $value->getArrangement());
        }

        return $this->render('patient/patientInfoPage.html.twig', 
            array(
                'title' => 'AOK | Patienten | Info',
                'arrangements' => $arrangements,
                'patient' => $patient,
            )
        );
    }

    
    /**
     * @Route("/patients/delete/{id}", name="patientDeletePage")
     */
    public function patientDeleteAction(Request $request, $id)
    {
        $patient = $this->getDoctrine()->getRepository('AppBundle:Patient')->findOneById($id);
        // Snapshot for logging
        $before = clone($patient);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($patient);
        $em->flush();

        // Log differences.
        $util = $this->get('util');
        $util->logAction($request, $id, $before, $patient);
        
        $this->addFlash('notice', 'Patient erfolgreich gelöscht');
        
        return $this->redirectToRoute('patientsPage');
        
    }


}
