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

class SysUserEditType extends AbstractType 
{    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\SysUser',
	        'validation_groups' => array('edit'),
	        'hospitals' => null,
	        'userGroups' => null,
	    ));
	}

    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
    	$hospitals = $options['hospitals'];
    	$userGroups = $options['userGroups'];

        $builder 
            ->add('firstName', TextType::class, array(
                'label' => 'Vorname', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'empty_data' => '',
                'attr' => array('class' => 'form-control')))
            ->add('lastName', TextType::class, array(
                'label' => 'Nachname', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'empty_data' => '',
                'attr' => array('class' => 'form-control')))
            ->add('email', EmailType::class, array(
                'label' => 'E-Mail', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('phoneNumber', TextType::class, array(
                'label' => 'Tel. Nummer', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'empty_data' => '',
                'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array(
                'label' => 'Adresse', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'empty_data' => '',
                'attr' => array('class' => 'form-control')))
            ->add('sex', ChoiceType::class, array(
                'label' => 'Geschlecht', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),                
                'choices'  => array(
                    'männlich' => 'männlich',
                    'weiblich' => 'weiblich',
                ),
                'placeholder' => 'Wählen Sie ein Geschlecht aus',
                'empty_data' => '',
            ))
            ->add('userGroup', ChoiceType::class, array(
                'label' => 'Benutzergruppe', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $userGroups,
                'choice_label' => function($userGroup, $key, $index) {
                    return $userGroup->getName().': '.$userGroup->getDescription();
                },                
            ))
            ->add('hospital', ChoiceType::class, array(
                'label' => 'Krankenhaus', 
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $hospitals,
                'choice_label' => function($hospital, $key, $index) {
                    return $hospital->getName().': '.$hospital->getDescription();
                },                
                'placeholder' => 'Keine Zuweisung: Alle Patienten sehbar',
            ))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary')))             
        ;
            
    }
}

?>