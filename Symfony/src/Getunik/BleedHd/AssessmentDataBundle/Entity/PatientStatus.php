<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PatientStatus
 */
class PatientStatus
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $lastUpdatedDate;

    /**
     * @var integer
     */
    private $lastUpdatedBy;

    /**
     * @var integer
     */
    private $patientId;

    /**
     * @var \DateTime
     */
    private $transplantDate;

    /**
     * @var string
     */
    private $transplantType;

    /**
     * @var string
     */
    private $transplantSource;


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
     * Set lastUpdatedDate
     *
     * @param \DateTime $lastUpdatedDate
     * @return PatientStatus
     */
    public function setLastUpdatedDate($lastUpdatedDate)
    {
        $this->lastUpdatedDate = $lastUpdatedDate;

        return $this;
    }

    /**
     * Get lastUpdatedDate
     *
     * @return \DateTime 
     */
    public function getLastUpdatedDate()
    {
        return $this->lastUpdatedDate;
    }

    /**
     * Set lastUpdatedBy
     *
     * @param integer $lastUpdatedBy
     * @return PatientStatus
     */
    public function setLastUpdatedBy($lastUpdatedBy)
    {
        $this->lastUpdatedBy = $lastUpdatedBy;

        return $this;
    }

    /**
     * Get lastUpdatedBy
     *
     * @return integer 
     */
    public function getLastUpdatedBy()
    {
        return $this->lastUpdatedBy;
    }

    /**
     * Set patientId
     *
     * @param integer $patientId
     * @return PatientStatus
     */
    public function setPatientId($patientId)
    {
        $this->patientId = $patientId;

        return $this;
    }

    /**
     * Get patientId
     *
     * @return integer 
     */
    public function getPatientId()
    {
        return $this->patientId;
    }

    /**
     * Set transplantDate
     *
     * @param \DateTime $transplantDate
     * @return PatientStatus
     */
    public function setTransplantDate($transplantDate)
    {
        $this->transplantDate = $transplantDate;

        return $this;
    }

    /**
     * Get transplantDate
     *
     * @return \DateTime 
     */
    public function getTransplantDate()
    {
        return $this->transplantDate;
    }

    /**
     * Set transplantType
     *
     * @param string $transplantType
     * @return PatientStatus
     */
    public function setTransplantType($transplantType)
    {
        $this->transplantType = $transplantType;

        return $this;
    }

    /**
     * Get transplantType
     *
     * @return string 
     */
    public function getTransplantType()
    {
        return $this->transplantType;
    }

    /**
     * Set transplantSource
     *
     * @param string $transplantSource
     * @return PatientStatus
     */
    public function setTransplantSource($transplantSource)
    {
        $this->transplantSource = $transplantSource;

        return $this;
    }

    /**
     * Get transplantSource
     *
     * @return string 
     */
    public function getTransplantSource()
    {
        return $this->transplantSource;
    }
}
