<?php 

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LoginType extends AbstractType 
{    
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
//         $builder 
//             ->add('E-Mail')
//             ->add('Password')
//             ->add('Save', SubmitType::class, array('label' => 'Create Task'))
//         ;
    }
}

?>