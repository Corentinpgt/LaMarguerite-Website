<?php
//----------------------------------------------------------------------
// src/Form/PartnerEditType.php
//----------------------------------------------------------------------
namespace App\Form;

use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

use App\Entity\Partner;

class PartnerEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

		$builder->add('name', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('url', TextType::class, array(
			'required'	=> true,
			'label'		=> false,
		));

		$builder->add('img', ImageType::class, array(
			'required'	=> false,
			'label'		=> false,
			'constraints'	=> array(new Valid())
		));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partner::class,
        ]);
    }
}
