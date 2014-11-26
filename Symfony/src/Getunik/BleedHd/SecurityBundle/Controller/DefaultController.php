<?php

namespace Getunik\BleedHd\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
    	$env = $this->container->get('kernel')->getEnvironment();
        return $this->render('GetunikBleedHdSecurityBundle:Default:index.html.twig', array('name' => $name, 'env' => $env));
    }
}
