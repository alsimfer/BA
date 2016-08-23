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

class PatientType extends AbstractType 
{    
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => 'AppBundle\Entity\Patient',
	        'validation_groups' => array(),
	        'hospitals' => null,
	        'sysUsers' => null,
	    ));
	}

    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
    	$hospitals = $options['hospitals'];
    	$sysUsers = $options['sysUsers'];

        $builder 
            ->add('firstName', TextType::class, array(
                'label' => 'Vorname', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('lastName', TextType::class, array(
                'label' => 'Nachname', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Geburtstag',
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
            ])
            ->add('sex', ChoiceType::class, array(
                'label' => 'Geschlecht', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control'),                
                'choices'  => array(
                    'männlich' => 'männlich',
                    'weiblich' => 'weiblich',
                ),
                'placeholder' => 'Wählen Sie ein Geschlecht aus',
            ))
            ->add('email', TextType::class, array(
                'label' => 'E-Mail', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'required' => false,
            ))
            ->add('phoneNumber', TextType::class, array(
                'label' => 'Tel. Nummer', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('address', TextType::class, array(
                'label' => 'Adresse', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control')))
            ->add('hospital', ChoiceType::class, array(
                'label' => 'Krankenhaus',
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $hospitals,
                'choice_label' => function($hospital, $key, $index) {
                    return $hospital->getName();
                },                
                'placeholder' => 'Wählen Sie ein Krankenhaus aus',
            ))
            ->add('sysUser', ChoiceType::class, array(
                'label' => 'Betreuer', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control'),
                'choices' => $sysUsers,
                'choice_label' => function($sysUser, $key, $index) {
                    return $sysUser->getFirstName().' '.$sysUser->getLastName();
                },                
                'placeholder' => 'Wählen Sie ein Betreuer aus',
            ))

            // Ensurance.
            ->add('krankenversicherungsart', ChoiceType::class, array(
                'label' => 'Krankenversicherungsart', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%'),                
                'choices'  => array(
                    'Gesetzliche Krankenversicherung (GKV)' => 'Gesetzliche Krankenversicherung (GKV)',
                    'Private Krankenversicherung (PKV)' => 'Private Krankenversicherung (PKV)',
                    'Selbstzahler' => 'Selbstzahler',
                    'Unbekannt' => 'Unbekannt',
                ),
                'placeholder' => 'Wählen Sie eine Krankenversicherungsart aus',
            ))
            ->add('krankenkassennummer', TextType::class, array(
                'label' => 'Krankenkassennummer-IK', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('krankenkasse', TextType::class, array(
                'label' => 'Krankenkasse', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('kassennameZurBedruckung', TextType::class, array(
                'label' => 'Kassenname zur Bedruckung', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('versichertennummer', TextType::class, array(
                'label' => 'Versichertennummer', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('egkVersichertenNr', TextType::class, array(
                'label' => 'eGK-Versicherten-Nr', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('kostentraegerabrechnungsbereich', TextType::class, array(
                'label' => 'Kostenträgerabrechnungsbereich', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('kvBereich', TextType::class, array(
                'label' => 'KV-Bereich', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('abrechnungsvknr', TextType::class, array(                                                                                    
                'label' => 'Abrechnungs-VKNR', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('sonstige', TextType::class, array(                                                                                                    
                'label' => 'Sonstige Kostenträger-Zusatzangabe', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('versichertenartmfr', TextType::class, array(                                                                                                                        
                'label' => 'Versichertenart-MFR', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('versichertenstatuskvk', TextType::class, array(                       
                'label' => 'Versichertenstatus-KVK', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))
            ->add('statusergaenzung', TextType::class, array(            
                'label' => 'Statusergänzung', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%')))

            ->add('validTill', DateType::class, [
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd.MM.yyyy',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'KV gültig bis (Monat/Jahr)',
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
            ])


           ->add('abrechnungsform', ChoiceType::class, array(
                'label' => 'Abrechnungsform', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%'),                
                'choices'  => array(
                    'Rechnung' => 'Rechnung',
                    'prästationär' => 'prästationär',
                    'privat' => 'privat',
                    'integrierte' => 'integrierte',
                ),
                'placeholder' => 'Wählen Sie eine Abrechnungsform aus',
            ))
            ->add('nachsorge', ChoiceType::class, array(
                'label' => 'Bezahlung der Nachsorge', 
                'label_attr' => array('class' => 'col-sm-4 col-form-label'),
                'attr' => array('class' => 'form-control', 'style' => 'width: 100%'),                
                'choices'  => array(
                    'wird von der Krankenkasse übernommen' => 'wird von der Krankenkasse übernommen',
                    'wird nicht von der Krankenkasse übernommen' => 'wird nicht von der Krankenkasse übernommen',
                    'Selbstzahler' => 'Selbstzahler',
                    'Unbekannt' => 'Unbekannt',
                ),
                'placeholder' => 'Wählen Sie die Bezahlung der Nachsorge aus',
            ))
            ->add('save', SubmitType::class, array('label' => 'Ok', 'attr' => array('class' => 'btn btn-primary')))            
        ;
            
    }
}

?>