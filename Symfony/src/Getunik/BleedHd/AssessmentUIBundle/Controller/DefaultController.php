<?php

/*
 * Copyright(c) 2015, getunik AG (http://www.getunik.com)
 * ALL Rights Reserved
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of getunik AG and its suppliers, if any.
 * The intellectual and technical concepts contained
 * herein are proprietary to getunik AG and its suppliers and
 * may be covered by Swiss and Foreign Patents, patents in
 * process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from getunik AG.
 */

namespace Getunik\BleedHd\AssessmentUIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Default controller for the BleedHD AngularJS App
 */
class DefaultController extends Controller
{
    public function indexAction()
    {
        $settings = $this->container->getParameter('getunik_bleed_hd_assessment_data.settings');
        return $this->render('GetunikBleedHdAssessmentUIBundle:Default:index.html.twig', array('settings' => $settings));
    }

    public function rootRedirectAction()
    {
        return $this->redirect($this->generateUrl('fos_user_security_login'));
    }
}
