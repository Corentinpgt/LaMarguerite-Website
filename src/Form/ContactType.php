<?php
//----------------------------------------------------------------------
// src/Form/ContactType.php
//----------------------------------------------------------------------
namespace App\Form;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

use App\Entity\Contact;

class ContactType extends AbstractType
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

		$builder->add('email', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('phone', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('message', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('captcha', Recaptcha3Type::class, [
			'constraints' => new Recaptcha3(['message' => 'There were problems with your captcha. Please try again or contact with support and provide following code(s): {{ errorCodes }}']),
			'action_name' => 'contact',
		]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
