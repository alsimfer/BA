<?php 

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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

use Symfony\Component\OptionsResolver\OptionsResolver;

class MedCheckupType extends AbstractType 
{    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\MedCheckup',
	        'patients' => null,
	        'sysUsers' => null,
	    ));
	}

	// Options[0] = patients, options[1] = sysUsers.
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
    	$patients = $options['patients'];
    	$sysUsers = $options['sysUsers'];

        $builder 
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
                'label' => 'Arzt', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $sysUsers,
                'choice_label' => function($sysUser, $key, $index) {
                    return $sysUser->getFirstName().' '.$sysUser->getLastName();
                },                
                'placeholder' => 'Wählen Sie einen Arzt aus',
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
        ;
    }
}

?>