<?php

namespace Getunik\BleedHd\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 */
class Client
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $randomId;

    /**
     * @var array
     */
    private $redirectUris;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var array
     */
    private $allowedGrantTypes;


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
     * Set name
     *
     * @param string $name
     * @return Client
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set randomId
     *
     * @param string $randomId
     * @return Client
     */
    public function setRandomId($randomId)
    {
        $this->randomId = $randomId;

        return $this;
    }

    /**
     * Get randomId
     *
     * @return string 
     */
    public function getRandomId()
    {
        return $this->randomId;
    }

    /**
     * Set redirectUris
     *
     * @param array $redirectUris
     * @return Client
     */
    public function setRedirectUris($redirectUris)
    {
        $this->redirectUris = $redirectUris;

        return $this;
    }

    /**
     * Get redirectUris
     *
     * @return array 
     */
    public function getRedirectUris()
    {
        return $this->redirectUris;
    }

    /**
     * Set secret
     *
     * @param string $secret
     * @return Client
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get secret
     *
     * @return string 
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * Set allowedGrantTypes
     *
     * @param array $allowedGrantTypes
     * @return Client
     */
    public function setAllowedGrantTypes($allowedGrantTypes)
    {
        $this->allowedGrantTypes = $allowedGrantTypes;

        return $this;
    }

    /**
     * Get allowedGrantTypes
     *
     * @return array 
     */
    public function getAllowedGrantTypes()
    {
        return $this->allowedGrantTypes;
    }
}
