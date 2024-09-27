<?php
//----------------------------------------------------------------------
// src/Form/UserType.php
//----------------------------------------------------------------------
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use App\Entity\Access;

class UserPwdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		// $builder->add('email', TextType::class, array(
		// 	'required'	=> true,
		// 	'label'		=> false,
		// ));

		// $builder->add('lastname', TextType::class, array(
		// 	'required'	=> true,
		// 	'label'		=> false,
		// ));
		//
		// $builder->add('firstname', TextType::class, array(
		// 	'required'	=> true,
		// 	'label'		=> false,
		// ));

        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => false,
            'first_options'  => ['label' => false],
            'second_options' => ['label' => false],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Access::class,
        ]);
    }
}
