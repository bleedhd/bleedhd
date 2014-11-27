<?php

namespace Getunik\BleedHd\SecurityBundle\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Http\HttpUtils;

use Doctrine\ORM\EntityManager;

use Getunik\Leech\AuthenticationBundle\Entity\User;

/**
 * Custom authentication success handler
 */
class AutoTokenHandler implements AuthenticationSuccessHandlerInterface
{
    protected $providerKey;
    protected $kernel;
    protected $clientId;
    protected $clientSecret;
    protected $targetPath;

    /**
    * Constructor
    * @param HttpKernelInterface   $kernel
    * @param string                $clientId
    * @param string                $clientSecret
    * @param string                $targetPath
    */
    public function __construct(HttpKernelInterface $kernel, $clientId, $clientSecret, $targetPath)
    {
        $this->kernel = $kernel;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
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
        $authRequest = Request::create(
            '/oauth/v2/token',
            'GET',
            array(
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'password',
                'username' => $request->get('_username'),
                'password' => $request->get('_password'),
            )
        );

        $response = $this->kernel->handle($authRequest, HttpKernelInterface::SUB_REQUEST, false);
        $auth = json_decode($response->getContent());

        // compute expiration date/time and add it to the token information
        $now = new \DateTime();
        $auth->expires_at = $now->add(new \DateInterval('PT' . 3600 . 'S'))->format(\DateTime::ISO8601);

        $session = $request->getSession();
        $session->set('getunik_bleed_hd_security.oauth_token', $auth);

        return new RedirectResponse($this->targetPath);
    }
}
