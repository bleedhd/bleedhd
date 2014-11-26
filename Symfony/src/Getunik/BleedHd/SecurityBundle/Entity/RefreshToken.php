<?php

namespace Getunik\BleedHd\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RefreshToken
 */
class RefreshToken
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $token;

    /**
     * @var integer
     */
    private $expiresAt;

    /**
     * @var string
     */
    private $scope;

    /**
     * @var \Getunik\BleedHd\SecurityBundle\Entity\Client
     */
    private $client;

    /**
     * @var \Getunik\BleedHd\SecurityBundle\Entity\FosUser
     */
    private $user;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return RefreshToken
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set expiresAt
     *
     * @param integer $expiresAt
     * @return RefreshToken
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return integer 
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set scope
     *
     * @param string $scope
     * @return RefreshToken
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get scope
     *
     * @return string 
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set client
     *
     * @param \Getunik\BleedHd\SecurityBundle\Entity\Client $client
     * @return RefreshToken
     */
    public function setClient(\Getunik\BleedHd\SecurityBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Getunik\BleedHd\SecurityBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set user
     *
     * @param \Getunik\BleedHd\SecurityBundle\Entity\FosUser $user
     * @return RefreshToken
     */
    public function setUser(\Getunik\BleedHd\SecurityBundle\Entity\FosUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Getunik\BleedHd\SecurityBundle\Entity\FosUser 
     */
    public function getUser()
    {
        return $this->user;
    }
}
