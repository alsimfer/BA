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

use AppBundle\Form\Type\MedCheckupType;
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
        $medCheckups = $this->getDoctrine()->getRepository('AppBundle:MedCheckup')->findRelevantToUser($this->getUser());

        return $this->render('medCheckup/medCheckupsPage.html.twig', 
            array(
                'title' => 'AOK | Untersuchungen',
                
                'medCheckups' => $medCheckups,
            )
        );
    }


    /**
     * @Route("/med-checkups/create", name="medCheckupCreatePage")
     */
    public function medCheckupCreateAction(Request $request)
    {
        $medCheckup = new MedCheckup();
        $before = clone($medCheckup);
        $user = $this->getUser();

        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findRelevantToUser($user);
        // If we are logged in as a Doctor, we can not choose any other Doctor for the checkup.
        if ($user->getUserGroup()->getId() === 4) {
            $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findById($user->getId());    
        } else {
            $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 4));    
        }        
        
        $form = $this->createForm(MedCheckupType::class, $medCheckup, array(
                'patients' => $patients,
                'sysUsers' => $sysUsers,
            )
        );
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $medCheckup->setType($form['type']->getData());
            $medCheckup->setPatient($form['patient']->getData());
            $medCheckup->setSysUser($form['sysUser']->getData());
            $medCheckup->setDateAndTime($form['dateAndTime']->getData());
            $medCheckup->setHeight($form['height']->getData());
            $medCheckup->setWeight($form['weight']->getData());
            $medCheckup->setWaist($form['waist']->getData());
            $medCheckup->setHips($form['hips']->getData());
            $medCheckup->setSource($form['source']->getData());

            $medCheckup->setArterielleHypertonie($form['arterielleHypertonie']->getData());
            $medCheckup->setArterielleHypertonieText($form['arterielleHypertonieText']->getData());
            $medCheckup->setAndereKardialeKomorbiditaeten($form['andereKardialeKomorbiditaeten']->getData());
            $medCheckup->setAndereKardialeKomorbiditaetenText($form['andereKardialeKomorbiditaetenText']->getData());
            $medCheckup->setInsulinpflichtigerDiabetes($form['insulinpflichtigerDiabetes']->getData());
            $medCheckup->setInsulinpflichtigerDiabetesText($form['insulinpflichtigerDiabetesText']->getData());
            $medCheckup->setNichtInsulinpflichtigerDiabetes($form['nichtInsulinpflichtigerDiabetes']->getData());
            $medCheckup->setNichtInsulinpflichtigerDiabetesText($form['nichtInsulinpflichtigerDiabetesText']->getData());
            $medCheckup->setPulmonaleKomorbiditaeten($form['pulmonaleKomorbiditaeten']->getData());
            $medCheckup->setPulmonaleKomorbiditaetenText($form['pulmonaleKomorbiditaetenText']->getData());
            $medCheckup->setFettstoffwechselstoerungen($form['fettstoffwechselstoerungen']->getData());
            $medCheckup->setFettstoffwechselstoerungenText($form['fettstoffwechselstoerungenText']->getData());
            $medCheckup->setEndokrineKomorbiditaeten($form['endokrineKomorbiditaeten']->getData());
            $medCheckup->setEndokrineKomorbiditaetenText($form['endokrineKomorbiditaetenText']->getData());
            $medCheckup->setGastroenterologischeKomorbiditaeten($form['gastroenterologischeKomorbiditaeten']->getData());
            $medCheckup->setGastroenterologischeKomorbiditaetenText($form['gastroenterologischeKomorbiditaetenText']->getData());
            $medCheckup->setVarikosis($form['varikosis']->getData());
            $medCheckup->setVarikosisText($form['varikosisText']->getData());
            $medCheckup->setOrthopaedischeKomorbiditaeten($form['orthopaedischeKomorbiditaeten']->getData());
            $medCheckup->setOrthopaedischeKomorbiditaetenText($form['orthopaedischeKomorbiditaetenText']->getData());
            $medCheckup->setNeurologischeKomorbiditaeten($form['neurologischeKomorbiditaeten']->getData());
            $medCheckup->setNeurologischeKomorbiditaetenText($form['neurologischeKomorbiditaetenText']->getData());
            $medCheckup->setRenaleKomorbiditaeten($form['renaleKomorbiditaeten']->getData());
            $medCheckup->setRenaleKomorbiditaetenText($form['renaleKomorbiditaetenText']->getData());
            $medCheckup->setOedeme($form['oedeme']->getData());
            $medCheckup->setOedemeText($form['oedemeText']->getData());
            $medCheckup->setOrgantransplantation($form['organtransplantation']->getData());
            $medCheckup->setOrgantransplantationText($form['organtransplantationText']->getData());
            $medCheckup->setPraderWilliSyndrom($form['praderWilliSyndrom']->getData());
            $medCheckup->setPraderWilliSyndromText($form['praderWilliSyndromText']->getData());
            $medCheckup->setNikotinabusus($form['nikotinabusus']->getData());
            $medCheckup->setNikotinabususText($form['nikotinabususText']->getData());
            $medCheckup->setAlkoholabusus($form['alkoholabusus']->getData());
            $medCheckup->setAlkoholabususText($form['alkoholabususText']->getData());
            $medCheckup->setWeiteres($form['weiteres']->getData());
            $medCheckup->setWeiteresText($form['weiteresText']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($medCheckup);
            $em->flush();

            $util = $this->get('util');        
            $util->logAction($request, $medCheckup->getId(), $before, $medCheckup);

            $this->addFlash('notice', 'Eine Untersuchung erfolgreich hinzugefügt');
            
            return $this->redirectToRoute('medCheckupsPage');
        }
        
        return $this->render('medCheckup/medCheckupCreatePage.html.twig', array(
            'title' => 'AOK | Untersuchungen | Erstellen',
            'form' => $form->createView(),
            
        ));
        
    }


    /**
     * @Route("/med-checkups/edit/{id}", name="medCheckupEditPage")
     */
    public function medCheckupEditAction(Request $request, $id)
    {
        $medCheckup = $this->getDoctrine()->getRepository('AppBundle:MedCheckup')->findOneById($id); 
        $before = clone($medCheckup);

        $patients = $this->getDoctrine()->getRepository('AppBundle:Patient')->findRelevantToUser($this->getUser());
        $sysUsers = $this->getDoctrine()->getRepository('AppBundle:SysUser')->findBy(array('userGroup' => 4));
        
        $form = $this->createForm(MedCheckupType::class, $medCheckup, array(
                'patients' => $patients,
                'sysUsers' => $sysUsers,
            )
        );
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $medCheckup->setType($form['type']->getData());
            $medCheckup->setPatient($form['patient']->getData());
            $medCheckup->setSysUser($form['sysUser']->getData());
            $medCheckup->setDateAndTime($form['dateAndTime']->getData());
            $medCheckup->setHeight($form['height']->getData());
            $medCheckup->setWeight($form['weight']->getData());
            $medCheckup->setWaist($form['waist']->getData());
            $medCheckup->setHips($form['hips']->getData());
            $medCheckup->setSource($form['source']->getData());

            $medCheckup->setArterielleHypertonie($form['arterielleHypertonie']->getData());
            $medCheckup->setArterielleHypertonieText($form['arterielleHypertonieText']->getData());
            $medCheckup->setAndereKardialeKomorbiditaeten($form['andereKardialeKomorbiditaeten']->getData());
            $medCheckup->setAndereKardialeKomorbiditaetenText($form['andereKardialeKomorbiditaetenText']->getData());
            $medCheckup->setInsulinpflichtigerDiabetes($form['insulinpflichtigerDiabetes']->getData());
            $medCheckup->setInsulinpflichtigerDiabetesText($form['insulinpflichtigerDiabetesText']->getData());
            $medCheckup->setNichtInsulinpflichtigerDiabetes($form['nichtInsulinpflichtigerDiabetes']->getData());
            $medCheckup->setNichtInsulinpflichtigerDiabetesText($form['nichtInsulinpflichtigerDiabetesText']->getData());
            $medCheckup->setPulmonaleKomorbiditaeten($form['pulmonaleKomorbiditaeten']->getData());
            $medCheckup->setPulmonaleKomorbiditaetenText($form['pulmonaleKomorbiditaetenText']->getData());
            $medCheckup->setFettstoffwechselstoerungen($form['fettstoffwechselstoerungen']->getData());
            $medCheckup->setFettstoffwechselstoerungenText($form['fettstoffwechselstoerungenText']->getData());
            $medCheckup->setEndokrineKomorbiditaeten($form['endokrineKomorbiditaeten']->getData());
            $medCheckup->setEndokrineKomorbiditaetenText($form['endokrineKomorbiditaetenText']->getData());
            $medCheckup->setGastroenterologischeKomorbiditaeten($form['gastroenterologischeKomorbiditaeten']->getData());
            $medCheckup->setGastroenterologischeKomorbiditaetenText($form['gastroenterologischeKomorbiditaetenText']->getData());
            $medCheckup->setVarikosis($form['varikosis']->getData());
            $medCheckup->setVarikosisText($form['varikosisText']->getData());
            $medCheckup->setOrthopaedischeKomorbiditaeten($form['orthopaedischeKomorbiditaeten']->getData());
            $medCheckup->setOrthopaedischeKomorbiditaetenText($form['orthopaedischeKomorbiditaetenText']->getData());
            $medCheckup->setNeurologischeKomorbiditaeten($form['neurologischeKomorbiditaeten']->getData());
            $medCheckup->setNeurologischeKomorbiditaetenText($form['neurologischeKomorbiditaetenText']->getData());
            $medCheckup->setRenaleKomorbiditaeten($form['renaleKomorbiditaeten']->getData());
            $medCheckup->setRenaleKomorbiditaetenText($form['renaleKomorbiditaetenText']->getData());
            $medCheckup->setOedeme($form['oedeme']->getData());
            $medCheckup->setOedemeText($form['oedemeText']->getData());
            $medCheckup->setOrgantransplantation($form['organtransplantation']->getData());
            $medCheckup->setOrgantransplantationText($form['organtransplantationText']->getData());
            $medCheckup->setPraderWilliSyndrom($form['praderWilliSyndrom']->getData());
            $medCheckup->setPraderWilliSyndromText($form['praderWilliSyndromText']->getData());
            $medCheckup->setNikotinabusus($form['nikotinabusus']->getData());
            $medCheckup->setNikotinabususText($form['nikotinabususText']->getData());
            $medCheckup->setAlkoholabusus($form['alkoholabusus']->getData());
            $medCheckup->setAlkoholabususText($form['alkoholabususText']->getData());
            $medCheckup->setWeiteres($form['weiteres']->getData());
            $medCheckup->setWeiteresText($form['weiteresText']->getData());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($medCheckup);
            $em->flush();

            $util = $this->get('util');        
            $util->logAction($request, $id, $before, $medCheckup);

            $this->addFlash('notice', 'Eine Untersuchung erfolgreich gespeichert');
            
            return $this->redirectToRoute('medCheckupsPage');
        }
        
        return $this->render('medCheckup/medCheckupEditPage.html.twig', array(
            'title' => 'AOK | Untersuchungen | Bearbeiten',
            'form' => $form->createView(),
            
        ));
        
    }
    

    /**
     * @Route("/med-checkups/info/{id}", name="medCheckupInfoPage")
     */
    public function medCheckupInfoAction(Request $request, $id)
    {
        $medCheckup = $this->getDoctrine()->getRepository('AppBundle:medCheckup')->findOneById($id);

        return $this->render('medCheckup/medCheckupInfoPage.html.twig', 
            array(
                'title' => 'AOK | Untersuchungen | Info',
                
                'medCheckup' => $medCheckup,
            )
        );
    }


    /**
     * @Route("/med-checkups/delete/{id}", name="medCheckupDeletePage")
     */
    public function medCheckupDeleteAction(Request $request, $id)
    {
        $medCheckup = $this->getDoctrine()->getRepository('AppBundle:MedCheckup')->findOneById($id);
        $before = clone($medCheckup);

        $em = $this->getDoctrine()->getManager();
        $em->remove($medCheckup);
        $em->flush();

        $util = $this->get('util');
        $util->logAction($request, $id, $before, $medCheckup);
        
        $this->addFlash('notice', 'Untersuchung erfolgreich gelöscht');
        
        return $this->redirectToRoute('medCheckupsPage');
        
    }

}
