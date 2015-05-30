<?php

namespace Biotopedia\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BiotopediaUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
