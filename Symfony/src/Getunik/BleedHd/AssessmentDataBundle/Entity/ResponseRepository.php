<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Entity;

use Doctrine\ORM\EntityRepository;


/**
 * ResponseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResponseRepository extends EntityRepository
{
    public function save(Response $response, $flush = false)
    {
        $this->_em->persist($response);
        if ($flush)
        {
            $this->_em->flush();
        }
    }

    public function update(Response $response)
    {
        $merged = $this->_em->merge($response);

        return $merged;
    }
}
