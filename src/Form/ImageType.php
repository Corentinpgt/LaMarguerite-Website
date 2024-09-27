<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('file', FileType::class, array(
			'required'	=>	false,
			'label'		=>	false,
			'constraints' => array(
				new File([
						'mimeTypes' 		=> 	Image::FORM_ALLOWED_MIME,
						'maxSize'			=>	Image::FORM_MAX_SIZE,
						'maxSizeMessage'	=>  Image::FORM_MAX_SIZE_MSG,
					]),
		   ),));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
