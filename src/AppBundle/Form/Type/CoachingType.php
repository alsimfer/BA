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

class CoachingType extends AbstractType 
{    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\Coaching',
	        'patients' => null,
	        'sysUsers' => null,
            'progress' => null,
            'cascade_validation' => true,
	    ));
	}

	// Options[0] = patients, options[1] = sysUsers.
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
    	$patients = $options['patients'];
    	$sysUsers = $options['sysUsers'];
        $progress = $options['progress'];

        $builder 
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
                'label' => 'Coach', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $sysUsers,
                'choice_label' => function($sysUser, $key, $index) {
                    return $sysUser->getFirstName().' '.$sysUser->getLastName();
                },                
                'placeholder' => 'Wählen Sie einen Coach aus',
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
            ->add('weekGoal', TextareaType::class, array(
                'label' => 'Ziel',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))            
            ->add('weight', IntegerType::class, array(                
                'label' => 'Aktuelles Gewicht',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))           
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary')))
        ;
    }
}

?>