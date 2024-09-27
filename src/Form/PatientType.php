<?php
//----------------------------------------------------------------------
// src/Form/PatientType.php
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


use App\Entity\Patient;
use App\Entity\Pathology;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
		$builder->add('firstname', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('lastname', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('birthdate', DateType::class, array(
			'required'	=> false,
			'widget' => 'single_text',
			'label'		=> false,
		));

		$builder->add('leftDate', DateType::class, array(
			'required'	=> false,
			'widget' => 'single_text',
			'label'		=> false,
		));

		$builder->add('email', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('phone', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));


		$builder->add('address', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('gender', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));
		
		$builder->add('pathology', EntityType::class, [
            'class' => Pathology::class,
            'choice_label' => "name", 
			'multiple' => true,
			'by_reference' => false,
			'expanded' => true,
			'required'	=> false,

        ]);


		$builder->add('origin', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('mobility', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('followUp', CheckboxType::class, array(
			'required'	=> false,
			'label'		=> false,
		));


		$builder->add('imgLaw', CheckboxType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('job', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('situation', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('nbrChildren', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
