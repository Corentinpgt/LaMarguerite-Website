<?php
//----------------------------------------------------------------------
// src/Form/ArticleType.php
//----------------------------------------------------------------------
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use App\Entity\Article;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		$builder->add('files', HiddenType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('eventDate', DateType::class, array(
			'required'	=> false,
			'label'		=> false,
		));

		$builder->add('title', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('body', TextareaType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' 	=> Article::class,
			'action'		=> 'add',
        ]);
    }

	public function getBlockPrefix(): string
	{
		return 'blog_article';
	}
}
