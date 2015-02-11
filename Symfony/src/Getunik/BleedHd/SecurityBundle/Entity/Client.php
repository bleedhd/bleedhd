<?php

namespace Getunik\BleedHd\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\OAuthServerBundle\Model\Client as BaseClient;

/**
 * Client
 */
class Client extends BaseClient
{
    /**
     * @var string
     */
    private $name;

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
}
