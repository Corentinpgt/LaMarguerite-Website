<?php
//----------------------------------------------------------------------
// src/Form/MembershipIndiType.php
//----------------------------------------------------------------------
namespace App\Form;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use App\Entity\MembershipIndividual;
use App\Entity\MembersAsso;

class MembershipIndiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
		$builder->add('date_creation', DateType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('name', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('birthDate', DateType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('address', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('mail', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('tel', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('job', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('imgLaw', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('member_of', EntityType::class, [
            'class' => MembersAsso::class,
            'choice_label' => 'name', 
			'required'	=> false,

        ]);
		


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MembershipIndividual::class,
        ]);
    }
}
