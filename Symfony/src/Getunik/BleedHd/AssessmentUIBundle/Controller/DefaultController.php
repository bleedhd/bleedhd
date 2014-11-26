<?php

namespace Getunik\BleedHd\AssessmentUIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GetunikBleedHdAssessmentUIBundle:Default:index.html.twig', array('name' => $name));
    }
}
