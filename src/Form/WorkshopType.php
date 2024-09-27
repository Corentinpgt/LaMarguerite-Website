<?php
//----------------------------------------------------------------------
// src/Form/WorkshopType.php
//----------------------------------------------------------------------
namespace App\Form;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use App\Entity\Workshop;
use App\Entity\WorkshopCategory;
use App\Entity\Contributor;

class WorkshopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
		$builder->add('name', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('contributor', EntityType::class, [
            'class' => Contributor::class,
            // 'choice_label' => , 
			'choice_label' => function($asso) {
				return $asso->getFirstname() . ' ' . $asso->getLastname() . ' | ' . $asso->getJob();
			},
        ]);

		$builder->add('workshop_category', EntityType::class, [
            'class' => WorkshopCategory::class,
            'choice_label' => 'name', 
        ]);

		$builder->add('info_workshop', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('activity_workshop', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('place', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('day', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('hours', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Workshop::class,
        ]);
    }
}
