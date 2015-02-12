<?php

namespace Getunik\BleedHd\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;


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
        $clientId = $this->container->getParameter('getunik_bleed_hd_security.auto_token_client.id');
        $clientSecret = $this->container->getParameter('getunik_bleed_hd_security.auto_token_client.secret');

        $user = $this->getUser();
        $session = $request->getSession();
        $token = $session->get('getunik_bleed_hd_security.oauth_token');

        if (empty($token)) {
            throw $this->createAccessDeniedException();
        }

        $refreshRequest = Request::create(
            '/oauth/v2/token',
            'GET',
            array(
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'refresh_token',
                'refresh_token' => $token->refresh_token,
            )
        );

        $response = $this->get('kernel')->handle($refreshRequest, HttpKernelInterface::SUB_REQUEST, false);
        $auth = json_decode($response->getContent());

        // compute expiration date/time and add it to the token information
        $now = new \DateTime();
        $auth->expires_at = $now->add(new \DateInterval('PT' . 3600 . 'S'))->format(\DateTime::ISO8601);
        $auth->uid = ($user instanceof User ? $user->getId() : -1);

        $session->set('getunik_bleed_hd_security.oauth_token', $auth);
        // refresh the session lifetime
        $session->migrate();

        return new JsonResponse($auth);
    }
}
