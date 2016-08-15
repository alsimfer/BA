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
     * @Route("/med-checkups/create", name="medCheckupCreatePage")
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
            ->add('height', IntegerType::class, array(
                'label' => 'Größe, cm',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
            ))
            
            ->add('waist', IntegerType::class, array(
                'label' => 'Taillenumfang, cm',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
            ))
            ->add('hips', IntegerType::class, array(
                'label' => 'Hüftumfang, cm',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')
            ))
            ->add('weight', NumberType::class, array(
                'label' => 'Gewicht, kg',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
            ))
            ->add('source', ChoiceType::class, array(
                'label' => 'Patientenherkunft', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices'  => array(
                    'Empfehlung vom Hausarzt' => 'Empfehlung vom Hausarzt',
                    'Empfehlung vom Facharzt' => 'Empfehlung vom Facharzt',
                    'Empfehlung eines anderen bariatrischen Chirurgen' => 'Empfehlung eines anderen bariatrischen Chirurgen',
                    'Information über Printmedien (allgemeine Zeitschriften, Fachzeitschriften etc.)' => 'Information über Printmedien (allgemeine Zeitschriften, Fachzeitschriften etc.)',
                    'Information über digitale Medien (Internet allgemein, Google-Suche etc.)' => 'Information über digitale Medien (Internet allgemein, Google-Suche etc.)',
                    'Information oder Empfehlung von Freunden, Verwandten oder Bekannten' => 'Information oder Empfehlung von Freunden, Verwandten oder Bekannten',
                    'Information oder Empfehlung von anderen Menschen mit morbider Adipositas' => 'Information oder Empfehlung von anderen Menschen mit morbider Adipositas',
                ),
                'placeholder' => 'Wählen Sie die Herkunft aus',
            ))

            ->add('arterielleHypertonie', CheckboxType::class, array(
                'label' => 'Arterielle Hypertonie', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('arterielleHypertonieText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('andereKardialeKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Andere kardiale Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('andereKardialeKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('insulinpflichtigerDiabetes', CheckboxType::class, array(
                'label' => 'Insulinpflichtiger Diabetes mellitus Typ 2 (IDDM)', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('insulinpflichtigerDiabetesText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('nichtInsulinpflichtigerDiabetes', CheckboxType::class, array(
                'label' => 'Nicht insulinpflichtiger Diabetes mellitus Typ 2 (IDDM)', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('nichtInsulinpflichtigerDiabetesText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('pulmonaleKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Pulmonale Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('pulmonaleKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('fettstoffwechselstoerungen', CheckboxType::class, array(
                'label' => 'Fettstoffwechselstörungen', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('fettstoffwechselstoerungenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('endokrineKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Endokrine Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('endokrineKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('gastroenterologischeKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Gastroenterologische Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('gastroenterologischeKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('varikosis', CheckboxType::class, array(
                'label' => 'Varikosis', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('varikosisText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('orthopaedischeKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Orthopädische Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('orthopaedischeKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('neurologischeKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Neurologische Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('neurologischeKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('renaleKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Renale Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('renaleKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('oedeme', CheckboxType::class, array(
                'label' => 'Ödeme', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('oedemeText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('organtransplantation', CheckboxType::class, array(
                'label' => 'Z. n. Organtransplantation', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('organtransplantationText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('praderWilliSyndrom', CheckboxType::class, array(
                'label' => 'PRADER-WILLI-Syndrom', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('praderWilliSyndromText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('nikotinabusus', CheckboxType::class, array(
                'label' => 'Nikotinabusus', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('nikotinabususText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('alkoholabusus', CheckboxType::class, array(
                'label' => 'Alkoholabusus', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('alkoholabususText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('weiteres', CheckboxType::class, array(
                'label' => 'Weiteres', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('weiteresText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
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

            $this->addFlash('notice', 'Eine Untersuchung erfolgreich hinzugefügt');
            
            return $this->redirectToRoute('medCheckupsPage');
        }
        
        return $this->render('medCheckup/medCheckupCreatePage.html.twig', array(
            'title' => 'AOK | Untersuchungen | Erstellen',
            'form' => $form->createView(),
            'user' => $sysUser
        ));
        
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
            ->add('height', IntegerType::class, array(
                'label' => 'Größe, cm',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
            ))
            
            ->add('waist', IntegerType::class, array(
                'label' => 'Taillenumfang, cm',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
            ))
            ->add('hips', IntegerType::class, array(
                'label' => 'Hüftumfang, cm',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')
            ))
            ->add('weight', NumberType::class, array(
                'label' => 'Gewicht, kg',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
            ))
            ->add('source', ChoiceType::class, array(
                'label' => 'Patientenherkunft', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices'  => array(
                    'Empfehlung vom Hausarzt' => 'Empfehlung vom Hausarzt',
                    'Empfehlung vom Facharzt' => 'Empfehlung vom Facharzt',
                    'Empfehlung eines anderen bariatrischen Chirurgen' => 'Empfehlung eines anderen bariatrischen Chirurgen',
                    'Information über Printmedien (allgemeine Zeitschriften, Fachzeitschriften etc.)' => 'Information über Printmedien (allgemeine Zeitschriften, Fachzeitschriften etc.)',
                    'Information über digitale Medien (Internet allgemein, Google-Suche etc.)' => 'Information über digitale Medien (Internet allgemein, Google-Suche etc.)',
                    'Information oder Empfehlung von Freunden, Verwandten oder Bekannten' => 'Information oder Empfehlung von Freunden, Verwandten oder Bekannten',
                    'Information oder Empfehlung von anderen Menschen mit morbider Adipositas' => 'Information oder Empfehlung von anderen Menschen mit morbider Adipositas',
                ),
                'placeholder' => 'Wählen Sie die Herkunft aus',
            ))

            ->add('arterielleHypertonie', CheckboxType::class, array(
                'label' => 'Arterielle Hypertonie', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('arterielleHypertonieText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('andereKardialeKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Andere kardiale Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('andereKardialeKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('insulinpflichtigerDiabetes', CheckboxType::class, array(
                'label' => 'Insulinpflichtiger Diabetes mellitus Typ 2 (IDDM)', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('insulinpflichtigerDiabetesText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('nichtInsulinpflichtigerDiabetes', CheckboxType::class, array(
                'label' => 'Nicht insulinpflichtiger Diabetes mellitus Typ 2 (IDDM)', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('nichtInsulinpflichtigerDiabetesText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('pulmonaleKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Pulmonale Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('pulmonaleKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('fettstoffwechselstoerungen', CheckboxType::class, array(
                'label' => 'Fettstoffwechselstörungen', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('fettstoffwechselstoerungenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('endokrineKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Endokrine Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('endokrineKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('gastroenterologischeKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Gastroenterologische Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('gastroenterologischeKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('varikosis', CheckboxType::class, array(
                'label' => 'Varikosis', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('varikosisText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('orthopaedischeKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Orthopädische Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('orthopaedischeKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('neurologischeKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Neurologische Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('neurologischeKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('renaleKomorbiditaeten', CheckboxType::class, array(
                'label' => 'Renale Komorbiditäten', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('renaleKomorbiditaetenText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('oedeme', CheckboxType::class, array(
                'label' => 'Ödeme', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('oedemeText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('organtransplantation', CheckboxType::class, array(
                'label' => 'Z. n. Organtransplantation', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('organtransplantationText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('praderWilliSyndrom', CheckboxType::class, array(
                'label' => 'PRADER-WILLI-Syndrom', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('praderWilliSyndromText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('nikotinabusus', CheckboxType::class, array(
                'label' => 'Nikotinabusus', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('nikotinabususText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('alkoholabusus', CheckboxType::class, array(
                'label' => 'Alkoholabusus', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('alkoholabususText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('weiteres', CheckboxType::class, array(
                'label' => 'Weiteres', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'required' => false
            ))
            ->add('weiteresText', TextType::class, array(
                'attr' => array('class' => 'form-control'),
            ))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary'))) 
            ->getForm();
        
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

            $this->addFlash('notice', 'Eine Untersuchung erfolgreich gespeichert');
            
            return $this->redirectToRoute('medCheckupsPage');
        }
        
        return $this->render('medCheckup/medCheckupEditPage.html.twig', array(
            'title' => 'AOK | Untersuchungen | Bearbeiten',
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
                'title' => 'AOK | Untersuchungen | Info',
                'user' => $sysUser,
                'medCheckup' => $medCheckup,
            )
        );
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
