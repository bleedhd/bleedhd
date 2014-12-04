<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Entity;

use Doctrine\ORM\EntityRepository;


class PatientRepository extends EntityRepository
{
    public function save(Patient $patient)
    {
        $this->_em->persist($patient);
        //$this->_em->flush();
    }

    public function update(Patient $patient)
    {
        $merged = $this->_em->merge($patient);
        //$this->_em->flush();

        return $merged;
    }
}
