<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Assessment
 */
class Assessment implements UpdateInformationInterface
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
     * @var string
     */
    private $questionnaire;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $startTime;

    /**
     * @var float
     */
    private $platelets;

    /**
     * @var string
     */
    private $remarks;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $statuses;

    /**
     * @var \Getunik\BleedHd\AssessmentDataBundle\Entity\Patient
     */
    private $patient;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->statuses = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return Assessment
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
     * @return Assessment
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
     * @return Assessment
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
     * Set questionnaire
     *
     * @param string $questionnaire
     * @return Assessment
     */
    public function setQuestionnaire($questionnaire)
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }

    /**
     * Get questionnaire
     *
     * @return string
     */
    public function getQuestionnaire()
    {
        return $this->questionnaire;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Assessment
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Assessment
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set platelets
     *
     * @param float $platelets
     * @return Assessment
     */
    public function setPlatelets($platelets)
    {
        $this->platelets = $platelets;

        return $this;
    }

    /**
     * Get platelets
     *
     * @return float
     */
    public function getPlatelets()
    {
        return $this->platelets;
    }

    /**
     * Set remarks
     *
     * @param string $remarks
     * @return Assessment
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }

    /**
     * Get remarks
     *
     * @return string
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Add statuses
     *
     * @param \Getunik\BleedHd\AssessmentDataBundle\Entity\Response $statuses
     * @return Assessment
     */
    public function addStatus(\Getunik\BleedHd\AssessmentDataBundle\Entity\Response $statuses)
    {
        $this->statuses[] = $statuses;

        return $this;
    }

    /**
     * Remove statuses
     *
     * @param \Getunik\BleedHd\AssessmentDataBundle\Entity\Response $statuses
     */
    public function removeStatus(\Getunik\BleedHd\AssessmentDataBundle\Entity\Response $statuses)
    {
        $this->statuses->removeElement($statuses);
    }

    /**
     * Get statuses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * Set patient
     *
     * @param \Getunik\BleedHd\AssessmentDataBundle\Entity\Patient $patient
     * @return Assessment
     */
    public function setPatient(\Getunik\BleedHd\AssessmentDataBundle\Entity\Patient $patient = null)
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * Get patient
     *
     * @return \Getunik\BleedHd\AssessmentDataBundle\Entity\Patient
     */
    public function getPatient()
    {
        return $this->patient;
    }
}
