<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GetunikBleedHdAssessmentDataBundle:Default:index.html.twig', array('name' => $name));
    }
}
