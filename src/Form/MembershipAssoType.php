<?php
//----------------------------------------------------------------------
// src/Form/MembershipAssoType.php
//----------------------------------------------------------------------
namespace App\Form;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use App\Entity\MembershipAsso;

class MembershipAssoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
		$builder->add('date_creation', DateType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('name_asso', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('address_asso', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('name_president', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('mail_president', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('tel_president', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('name_contact', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('position_contact', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('mail_contact', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('tel_contact', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MembershipAsso::class,
        ]);
    }
}
