<?php
//----------------------------------------------------------------------
// src/Form/MembersIndiType.php
//----------------------------------------------------------------------
namespace App\Form;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use App\Entity\MembersIndi;
use App\Entity\MembersAsso;


class MembersIndiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        

		$builder->add('name', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('membership_date', DateType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('payment_date', DateType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('payment', IntegerType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('birthdate', DateType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('address', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('email', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('phone', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('job', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('imgLaw', CheckboxType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('members_of', EntityType::class, [
            'class' => MembersAsso::class,
            'choice_label' => 'name', 
			'required'	=> false,

        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MembersIndi::class,
        ]);
    }
}
