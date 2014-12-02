<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\SecurityContextInterface;


class PatientStatusRepository extends EntityRepository
{
    private $context;

    public function setSecurityContext(SecurityContextInterface $context)
    {
        $this->context = $context;
    }

    public function save(PatientStatus $status)
    {
        $this->_em->persist($status);
        $this->_em->flush();
    }

    public function update(PatientStatus $status)
    {
        //var_dump($this->context->getToken()->getUser());

        $merged = $this->_em->merge($status);
        $this->_em->flush();

        return $merged;
    }
}
