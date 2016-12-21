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

class ProgressType extends AbstractType 
{    
	
    public function buildForm(FormBuilderInterface $builder) 
    {
        $builder         
            ->add('weight', NumberType::class, array(
                'label' => 'Aktuelles Gewicht',
                'label_attr' => array('class' => 'col-sm-2 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'empty_data' => ''
            ))                        
        ;
    }
}

?>