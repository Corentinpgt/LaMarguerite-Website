<?php
//----------------------------------------------------------------------
// src/Form/PatientSophroType.php
//----------------------------------------------------------------------
namespace App\Form;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


use App\Entity\PatientSophro;

class PatientSophroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
		$builder->add('health', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PatientSophro::class,
        ]);
    }
}
