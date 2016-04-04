<?php

namespace Getunik\BleedHd\SecurityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class RegistrationFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		// add your custom field
		$builder->add('roles', ChoiceType::class, array(
			'choices_as_values' => true,
			'multiple' => true,
			'choices' => array(
				'Reader' => 'ROLE_READER',
				'Assessor' => 'ROLE_ASSESSOR',
				'Supervisor' => 'ROLE_SUPERVISOR',
				'Admin' => 'ROLE_ADMIN',
			),
		));
		$builder->add('fullName');
	}

	public function getParent()
	{
		return 'FOS\UserBundle\Form\Type\RegistrationFormType';
	}

	public function getName()
	{
		return 'getunik_bleed_hd_user_registration';
	}
}
