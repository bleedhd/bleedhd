<?php

namespace Getunik\BleedHd\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function getTokenAction(Request $request)
    {
        $user = $this->getUser();
        $session = $request->getSession();
        $token = $session->get('getunik_bleed_hd_security.oauth_token');

        // refresh the session lifetime
        $session->migrate();

        return new JsonResponse($token);
    }

    public function refreshTokenAction(Request $request)
    {
        if (!$this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        try
        {
            $helper = $this->get('getunik_bleed_hd_security.oauth_helper');
            $auth = $helper->refreshToken($this->getUser());

            if (empty($auth)) {
                throw $this->createAccessDeniedException();
            }

            return new JsonResponse($auth);
        }
        catch (\Exception $e)
        {
            return $this->createAccessDeniedException();
        }
    }
}
