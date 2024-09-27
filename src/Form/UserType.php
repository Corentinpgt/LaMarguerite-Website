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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use App\Entity\Access;
use App\Entity\Employee;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
		$builder->add('email', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('roles', ChoiceType::class, [
            'required' => true,
            'label' => false,
            'multiple' => true,
            'expanded' => true, // true pour des cases à cocher, false pour une liste déroulante multiple
            'choices' => [
                'Administrateur' => 'ROLE_ADMIN',
                'Administration' => 'ROLE_MANAGE',
                'Diététicien(ne)' => 'ROLE_DIET',
                'Sophrologue' => 'ROLE_SOPHRO',

                // Ajoutez d'autres rôles ici
            ],
        ]);

		$builder->add('lastname', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('firstname', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('employee', EntityType::class, [
            'class' => Employee::class,
            'choice_label' => function($employee) {
				return $employee->getFirstname() . ' ' . $employee->getLastname() . ' | ' . $employee->getJob();
			}, 
			'required'	=> false,

        ]);

        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
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
