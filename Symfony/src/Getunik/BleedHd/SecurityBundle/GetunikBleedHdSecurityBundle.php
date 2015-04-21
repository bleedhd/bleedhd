<?php

namespace Getunik\BleedHd\SecurityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GetunikBleedHdSecurityBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
