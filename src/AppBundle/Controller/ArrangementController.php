<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\Type\ArrangementType;
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
        $arrangements = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findBy(array(), array('id' => 'DESC'), 1000, 0);

        return $this->render('arrangement/arrangementsPage.html.twig', 
            array(
                'title' => 'AOK | Kurse',
                'arrangements' => $arrangements,
            )
        );
    }


    /**
     * @Route("/arrangements/create", name="createArrangementPage")
     */
    public function arrangementCreateAction(Request $request)
    {
        $arrangement = new Arrangement();
        $before = clone($arrangement);

        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 6));

        $form = $this->createForm(ArrangementType::class, $arrangement, array('sysUsers' => $sysUsers));
        
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

            $util = $this->get('util');
            $util->logAction($request, $arrangement->getId(), $before, $arrangement);

            $this->addFlash('notice', 'Ein neuer Kurs erfolgreich hinzugefügt');
            
            return $this->redirectToRoute('arrangementsPage');
        }
        
        return $this->render('arrangement/arrangementCreatePage.html.twig', array(
            'title' => 'AOK | Kurse',
            'form' => $form->createView(),
        ));
        
    }


    /**
     * @Route("/arrangements/edit/{id}", name="arrangementEditPage")
     */
    public function arrangementEditAction(Request $request, $id)
    {
        $arrangement = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findOneById($id);
        $before = clone($arrangement);

        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 6));

        $form = $this->createForm(ArrangementType::class, $arrangement, array('sysUsers' => $sysUsers));
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $arrangement->setName($form['name']->getData());
            $arrangement->setDescription($form['description']->getData());
            $arrangement->setDateTime($form['dateTime']->getData());
            $arrangement->setMaxParticipants($form['maxParticipants']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($arrangement);
            $em->flush();

            $util = $this->get('util');
            $util->logAction($request, $id, $before, $arrangement);

            $this->addFlash('notice', 'Kurs erfolgreich gespeichert');

            return $this->redirectToRoute('arrangementsPage');
        }
        
        return $this->render('arrangement/arrangementEditPage.html.twig', array(
            'title' => 'AOK | Kurse',
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/arrangements/info/{id}", name="arrangementInfoPage")
     */
    public function arrangementInfoAction(Request $request, $id)
    {
        $arrangement = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findOneById($id);
        $patArrRefs = $this->getDoctrine()->getRepository('AppBundle:PatientArrangementReference')->findByArrangement($id);
        $patients = array();
        foreach ($patArrRefs as $key => $value) {
            array_push($patients, $value->getPatient());
        }

        return $this->render('arrangement/arrangementInfoPage.html.twig', 
            array(
                'title' => 'AOK | Kurse',
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
        $arrangement = $this->getDoctrine()->getRepository('AppBundle:Arrangement')->findOneById($id);
        $before = clone($arrangement);

        $em = $this->getDoctrine()->getManager();
        $em->remove($arrangement);
        $em->flush();

        $util = $this->get('util');
        $util->logAction($request, $id, $before, $arrangement);
        
        $this->addFlash('notice', 'Kurs erfolgreich gelöscht');
        
        return $this->redirectToRoute('arrangementsPage');
        
    }

}
