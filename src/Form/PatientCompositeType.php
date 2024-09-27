<?php
// src/Form/PatientCompositeType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

use App\Form\PatientType;
use App\Form\PatientDietType;
use App\Form\PatientSophroType;

class PatientCompositeType extends AbstractType
{
	private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

		if ($this->security->isGranted('ROLE_ADMIN')) {

			$builder->add('patient', PatientType::class, [
				'label' => false,
			]);
			$builder->add('patientDiet', PatientDietType::class, [
				'label' => false,
			]);
			$builder->add('patientSophro', PatientSophroType::class, [
				'label' => false,
			]);

		}
		elseif ($this->security->isGranted('ROLE_SOPHRO')) {
			
			$builder->add('patient', PatientType::class, [
				'label' => false,
			]);
			$builder->add('patientSophro', PatientSophroType::class, [
				'label' => false,
			]);
		}
		elseif ($this->security->isGranted('ROLE_DIET')) {
			
			$builder->add('patient', PatientType::class, [
				'label' => false,
			]);
			$builder->add('patientDiet', PatientDietType::class, [
				'label' => false,
			]);
		}

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Pas de classe de données spécifique
        ]);
    }
}