<?php
//----------------------------------------------------------------------
// src/Form/website/MembershipAssoTypeOut.php
//----------------------------------------------------------------------
namespace App\Form\website;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

use App\Entity\MembershipAsso;

class MembershipAssoTypeOut extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        

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

		$builder->add('captcha', Recaptcha3Type::class, [
			'constraints' => new Recaptcha3(['message' => 'There were problems with your captcha. Please try again or contact with support and provide following code(s): {{ errorCodes }}']),
			'action_name' => 'membership_asso',
		]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MembershipAsso::class,
        ]);
    }
}
