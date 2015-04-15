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

    /**
     * The only purpose of this action is to keep the user's session alive...
     * With PHP session handling, any session that has not been accessed for more
     * than 1440 seconds (24 min; default value in php.ini) "may" be garbage collected.
     * Since all REST requests don't actually access the session (as it should be),
     * the sessions tend to expire after the 24 minutes unless we ping the server
     * with a non-REST-ful request every now and then.
     *
     * See http://php.net/manual/en/session.configuration.php for more information
     */
    public function pingAction()
    {
        return new JsonResponse(array());
    }
}
