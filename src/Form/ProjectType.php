<?php
//----------------------------------------------------------------------
// src/Form/ProjectType.php
//----------------------------------------------------------------------
namespace App\Form;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use App\Entity\Project;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {        
		$builder->add('name', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('description', TextareaType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('img', ImageType::class, array(
			'required'	=> true,
			'label'		=> false,
			'constraints'	=> array(new Valid())
		));

		$builder->add('link_label', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('link', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
