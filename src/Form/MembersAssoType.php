<?php
//----------------------------------------------------------------------
// src/Form/MembersAssoType.php
//----------------------------------------------------------------------
namespace App\Form;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use App\Entity\MembersAsso;

class MembersAssoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        

		$builder->add('name', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('img', ImageType::class, array(
			'required'	=> false,
			'label'		=> false,
			'constraints'	=> array(new Valid())
		));

		$builder->add('description', TextType::class, array(
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

		$builder->add('address', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('president_name', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('president_email', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('president_phone', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('contact_name', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('contact_job', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('contact_email', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('contact_phone', TextType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MembersAsso::class,
        ]);
    }
}
