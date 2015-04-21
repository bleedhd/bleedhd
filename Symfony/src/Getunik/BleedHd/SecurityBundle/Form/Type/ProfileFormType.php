<?php

namespace Getunik\BleedHd\SecurityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class ProfileFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('fullName');
	}

	public function getParent()
	{
		return 'fos_user_profile';
	}

	public function getName()
	{
		return 'getunik_bleed_hd_user_profile';
	}
}
