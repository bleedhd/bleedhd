<?php

namespace Getunik\BleedHd\AssessmentUIBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class SecurityController extends BaseSecurityController
{
    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {
        $version = $this->get('getunik.bleed_hd_data.version_service');

        $data['test'] = $version->getVersion();
        return $this->render('FOSUserBundle:Security:login.html.twig', $data);
    }
}
