<?php

namespace Getunik\BleedHd\SecurityBundle\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

use Getunik\BleedHd\SecurityBundle\Entity\User;
use Getunik\BleedHd\SecurityBundle\Service\OAuthHelper;


/**
 * Custom authentication success handler
 */
class AutoTokenHandler implements AuthenticationSuccessHandlerInterface
{
    protected $helper;
    protected $targetPath;

    /**
    * Constructor
    * @param HttpKernelInterface   $kernel
    * @param string                $clientId
    * @param string                $clientSecret
    * @param string                $targetPath
    */
    public function __construct(OAuthHelper $helper, $targetPath)
    {
        $this->helper = $helper;
        $this->targetPath = $targetPath;
    }

    /**
    * This is called when an interactive authentication attempt succeeds. This
    * is called by authentication listeners inheriting from AbstractAuthenticationListener.
    * @param Request        $request
    * @param TokenInterface $token
    * @return Response The response to return
    */
    function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $this->helper->generateToken($token->getUser(), $request->get('_username'), $request->get('_password'));
        return new RedirectResponse($this->determineTargetUrl($request));
    }

    protected function determineTargetUrl(Request $request)
    {
        if ($targetUrl = $request->get('_target_path', null)) {
            if (!empty($targetUrl)) {
                return $targetUrl;
            }
        }

        if ($targetUrl = $request->getSession()->get('authentication_target_path')) {
            $request->getSession()->remove('authentication_target_path');
            return $targetUrl;
        }

        return $this->targetPath;
    }
}
