<?php

namespace Getunik\BleedHd\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GetunikBleedHdSecurityBundle:Default:index.html.twig', array('name' => $name));
    }
}
