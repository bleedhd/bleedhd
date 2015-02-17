<?php

namespace Getunik\BleedHd\SecurityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class RegistrationFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		// add your custom field
		$builder->add('roles', 'collection', array(
			'type'   => 'choice',
			'options'  => array(
				'choices'  => array(
					'ROLE_READER' => 'Reader',
					'ROLE_EDITOR' => 'Editor',
				),
			)
		));
	}

	public function getParent()
	{
		return 'fos_user_registration';
	}

	public function getName()
	{
		return 'getunik_bleed_hd_user_registration';
	}
}
