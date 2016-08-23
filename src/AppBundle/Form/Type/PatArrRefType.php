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

class PatArrRefType extends AbstractType 
{    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\PatientArrangementReference',
	        'patients' => null,
	        'arrangements' => null,
	    ));
	}
	
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
    	$patients = $options['patients'];
    	$arrangements = $options['arrangements'];

        $builder 
            ->add('patient', ChoiceType::class, array(
                'label' => 'Patient', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $patients,
                'choice_label' => function($patient, $key, $index) {
                    $birthDate = '';
                    if (
                        is_null($patient->getBirthDate()) === FALSE
                        && $patient->getBirthDate()->format('d.m.Y') !== '30.11.-0001'
                    ) {
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
        ;
    }
}

?>