<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Assessment
 */
class Assessment implements UpdateInformationInterface, CreationInformationInterface
{
    const PROGRESS_COMPLETE = 'completed';
    const PROGRESS_TENTATIVE = 'tentative';

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
     * @var \Getunik\BleedHd\AssessmentDataBundle\Entity\Patient
     */
    private $patient;

    /**
     * Constructor
     */
    public function __construct()
    {
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
    /**
     * @var array
     */
    private $result;


    /**
     * Set result
     *
     * @param array $result
     * @return Assessment
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get result
     *
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }
    /**
     * @var datetimez
     */
    private $createdDate;

    /**
     * @var integer
     */
    private $createdBy;


    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return Assessment
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return Assessment
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $responses;


    /**
     * Add responses
     *
     * @param \Getunik\BleedHd\AssessmentDataBundle\Entity\Response $responses
     * @return Assessment
     */
    public function addResponse(\Getunik\BleedHd\AssessmentDataBundle\Entity\Response $responses)
    {
        $this->responses[] = $responses;

        return $this;
    }

    /**
     * Remove responses
     *
     * @param \Getunik\BleedHd\AssessmentDataBundle\Entity\Response $responses
     */
    public function removeResponse(\Getunik\BleedHd\AssessmentDataBundle\Entity\Response $responses)
    {
        $this->responses->removeElement($responses);
    }

    /**
     * Get responses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResponses()
    {
        return $this->responses;
    }
    /**
     * @var string
     */
    private $progress;


    /**
     * Set progress
     *
     * @param string $progress
     * @return Assessment
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Get progress
     *
     * @return string
     */
    public function getProgress()
    {
        return $this->progress;
    }
}
