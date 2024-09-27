<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class DocumentType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('document', FileType::class, array(
			'label' 		=> 	false,
			'required'		=>	false,
			'constraints' => array(
				new File([
					'mimeTypes' => 	array(
						'application/pdf',
					),
				]),
			),
		));
	}
	

    public function configureOptions(OptionsResolver $resolver)
    {
		$resolver->setDefaults(array(
		));
    }

    public function getBlockPrefix(): string
    {
		return 'document';
    }
}