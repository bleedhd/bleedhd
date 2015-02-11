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

        return new JsonResponse($token);
    }

    public function refreshTokenAction()
    {
        $request = Request::create(
            '/getToken',
            'GET'
        );

        $kernel = $this->container->get('kernel');

        $response = $kernel->handle($request, HttpKernelInterface::SUB_REQUEST, false);

        return new JsonResponse(array('fib' => array(1, 1, 2, 3, 5, 8, 13), 'res' => $response->getContent()));
    }
}
