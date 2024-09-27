<?php
//----------------------------------------------------------------------
// src/Form/website/MembershipIndiTypeOut.php
//----------------------------------------------------------------------
namespace App\Form\website;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

use App\Entity\MembershipIndividual;
use App\Entity\MembersAsso;

class MembershipIndiTypeOut extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        

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

		$builder->add('imgLaw', CheckboxType::class, [
			'label'    => false,
			'required' => false,
		]);

		$builder->add('member_of', EntityType::class, [
            'class' => MembersAsso::class,
            'choice_label' => 'name', 
			'required'	=> false,

        ]);

		$builder->add('captcha', Recaptcha3Type::class, [
			'constraints' => new Recaptcha3(['message' => 'There were problems with your captcha. Please try again or contact with support and provide following code(s): {{ errorCodes }}']),
			'action_name' => 'membership_indi',
		]);

		// $builder->add('asso', EntityType::class, array(
		// 	'class' => Members::class,
		// 	'choice_label' => 'name',
		// 	'required'	=> false,
		// ));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MembershipIndividual::class,
        ]);
    }
}
