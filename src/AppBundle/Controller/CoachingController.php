<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\Type\CoachingType; 

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
        $coachings = $this->getDoctrine()->getRepository('AppBundle:Coaching')->findRelevantToUser($request->attributes->get('user'));

        return $this->render('coaching/coachingsPage.html.twig', 
            array(
                'title' => 'AOK | Coachings',
                
                'coachings' => $coachings,
            )
        );
    }


    /**
     * @Route("/coachings/create", name="coachingCreatePage")
     */
    public function coachingCreateAction(Request $request)
    {
        $coaching = new Coaching();
        $before = clone($coaching);
        $user = $request->attributes->get('user');

        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findRelevantToUser($user);

        // If we are logged in as a Coach, we can not choose any other Coach.
        if ($user->getUserGroup()->getId() === 5) {
            $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findById($user->getId());    
        } else {
            $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 5));    
        }
        
        $form = $this->createForm(CoachingType::class, $coaching, array(
                'patients' => $patients,
                'sysUsers' => $sysUsers,
            ));

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $coaching->setPatient($form['patient']->getData());
            $coaching->setSysUser($form['sysUser']->getData());
            $coaching->setDateAndTime($form['dateAndTime']->getData());
            $coaching->setWeekGoal($form['weekGoal']->getData());
                        
            $em = $this->getDoctrine()->getManager();
            $em->persist($coaching);
            $em->flush();

            $util = $this->get('util');        
            $util->logAction($request, $coaching->getId(), $before, $coaching);

            $this->addFlash('notice', 'Ein Coaching erfolgreich hinzugefügt');
            
            return $this->redirectToRoute('coachingsPage');
        }
        
        return $this->render('coaching/coachingCreatePage.html.twig', array(
            'title' => 'AOK | Coachings | Erstellen',
            'form' => $form->createView(),
            
        ));
        
    }


    /**
     * @Route("/coachings/edit/{id}", name="coachingEditPage")
     */
    public function coachingEditAction(Request $request, $id)
    {
        $coaching = $this->getDoctrine()->getRepository('AppBundle:Coaching')->findOneById($id);      
        $before = clone($coaching);

        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findRelevantToUser($request->attributes->get('user'));
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 5));
        
        $form = $this->createForm(CoachingType::class, $coaching, array(
                'patients' => $patients,
                'sysUsers' => $sysUsers,
            ));
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $coaching->setPatient($form['patient']->getData());
            $coaching->setSysUser($form['sysUser']->getData());
            $coaching->setDateAndTime($form['dateAndTime']->getData());
            $coaching->setWeekGoal($form['weekGoal']->getData());
            
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($coaching);
            $em->flush();

            $util = $this->get('util');
            $util->logAction($request, $id, $before, $coaching);

            $this->addFlash('notice', 'Das Coaching erfolgreich gespeichert');
            
            return $this->redirectToRoute('coachingsPage');
        }
        
        return $this->render('coaching/coachingEditPage.html.twig', array(
            'title' => 'AOK | Coachings | Bearbeiten',
            'form' => $form->createView(),
            
        ));
        
    }
    

    /**
     * @Route("/coachings/info/{id}", name="coachingInfoPage")
     */
    public function coachingInfoAction(Request $request, $id)
    {
        $coaching = $this->getDoctrine()->getRepository('AppBundle:Coaching')->findOneById($id);

        return $this->render('coaching/coachingInfoPage.html.twig', 
            array(
                'title' => 'AOK | Coachings | Info',
                
                'coaching' => $coaching,
            )
        );
    }


    /**
     * @Route("/coachings/delete/{id}", name="coachingDeletePage")
     */
    public function coachingDeleteAction(Request $request, $id)
    {
        $coaching = $this->getDoctrine()->getRepository('AppBundle:Coaching')->findOneById($id);
        $before = clone($coaching);

        $em = $this->getDoctrine()->getManager();
        $em->remove($coaching);
        $em->flush();

        $util = $this->get('util');
        $util->logAction($request, $id, $before, $coaching);

        $this->addFlash('notice', 'Coaching erfolgreich gelöscht');
        
        return $this->redirectToRoute('coachingsPage');
        
    }

}
