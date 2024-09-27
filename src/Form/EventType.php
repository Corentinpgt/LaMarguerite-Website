<?php
//----------------------------------------------------------------------
// src/Form/EventType.php
//----------------------------------------------------------------------
namespace App\Form;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use App\Entity\Event;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
		$builder->add('title', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('description', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('place', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('date', DateType::class, array(
			'required'	=> true,
			'label'		=> false,
		));


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
