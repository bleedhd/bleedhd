<?php

namespace Getunik\BleedHd\SecurityBundle\Service;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use FOS\OAuthServerBundle\Model\TokenManagerInterface;
use Getunik\BleedHd\SecurityBundle\Entity\User;


/**
 * Custom authentication success handler
 */
class OAuthHelper
{
    private $kernel;
    private $session;
    private $accessTokenManager;
    private $refreshTokenManager;
    private $clientId;
    private $clientSecret;



    /**
    * Constructor
    * @param HttpKernelInterface   $kernel
    * @param SessionInterface      $session
    * @param string                $clientId
    * @param string                $clientSecret
    */
    public function __construct(HttpKernelInterface $kernel, SessionInterface $session, TokenManagerInterface $accessTokenManager, TokenManagerInterface $refreshTokenManager, $clientId, $clientSecret)
    {
        $this->kernel = $kernel;
        $this->session = $session;
        $this->accessTokenManager = $accessTokenManager;
        $this->refreshTokenManager = $refreshTokenManager;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
    */
    public function generateToken(User $user, $username, $password)
    {
        $authRequest = Request::create(
            '/oauth/v2/token',
            'GET',
            array(
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'password',
                'username' => $username,
                'password' => $password,
            )
        );

        $response = $this->kernel->handle($authRequest, HttpKernelInterface::SUB_REQUEST, false);
        $auth = json_decode($response->getContent());
        $auth = $this->processToken($auth, $user);

        return $auth;
    }

    /**
     */
    public function refreshToken(User $user)
    {
        $token = $this->session->get('getunik_bleed_hd_security.oauth_token');

        if (empty($token)) {
            return NULL;
        }

        $refreshRequest = Request::create(
            '/oauth/v2/token',
            'GET',
            array(
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'refresh_token',
                'refresh_token' => $token->refresh_token,
            )
        );

        $response = $this->kernel->handle($refreshRequest, HttpKernelInterface::SUB_REQUEST, false);
        $auth = json_decode($response->getContent());
        $auth = $this->processToken($auth, $user);

        return $auth;
    }

    public function disableToken()
    {
        $token = $this->session->get('getunik_bleed_hd_security.oauth_token');

        if (empty($token)) {
            return NULL;
        }

        $this->accessTokenManager->deleteExpired();
        $accessToken = $this->accessTokenManager->findTokenByToken($token->access_token);
        if ($accessToken)
        {
            $this->accessTokenManager->deleteToken($accessToken);
        }

        $this->refreshTokenManager->deleteExpired();
        $refreshToken = $this->refreshTokenManager->findTokenByToken($token->refresh_token);
        if ($refreshToken)
        {
            $this->refreshTokenManager->deleteToken($refreshToken);
        }
    }

    protected function processToken($auth, $user)
    {
        if (isset($auth->error))
        {
            throw new \Exception("Token error ({$auth->error}): {$auth->error_description}");
        }

        // compute expiration date/time and add it to the token information
        $now = new \DateTime();
        $auth->expires_at = $now->add(new \DateInterval('PT' . $auth->expires_in . 'S'))->format(\DateTime::ISO8601);
        $auth->uid = ($user instanceof User ? $user->getId() : -1);

        $this->session->set('getunik_bleed_hd_security.oauth_token', $auth);
        // refresh the session lifetime
        $this->session->migrate();

        return $auth;
    }
}
