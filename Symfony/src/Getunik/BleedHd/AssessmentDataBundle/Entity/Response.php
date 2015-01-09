<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Response
 */
class Response implements UpdateInformationInterface
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
    private $assessmentId;

    /**
     * @var string
     */
    private $questionSlug;

    /**
     * @var array
     */
    private $result;

    /**
     * @var \Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment
     */
    private $assessment;


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
     * @return Response
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
     * @return Response
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
     * Set assessmentId
     *
     * @param integer $assessmentId
     * @return Response
     */
    public function setAssessmentId($assessmentId)
    {
        $this->assessmentId = $assessmentId;

        return $this;
    }

    /**
     * Get assessmentId
     *
     * @return integer
     */
    public function getAssessmentId()
    {
        return $this->assessmentId;
    }

    /**
     * Set questionSlug
     *
     * @param string $questionSlug
     * @return Response
     */
    public function setQuestionSlug($questionSlug)
    {
        $this->questionSlug = $questionSlug;

        return $this;
    }

    /**
     * Get questionSlug
     *
     * @return string
     */
    public function getQuestionSlug()
    {
        return $this->questionSlug;
    }

    /**
     * Set result
     *
     * @param array $result
     * @return Response
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
     * Set assessment
     *
     * @param \Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment $assessment
     * @return Response
     */
    public function setAssessment(\Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment $assessment = null)
    {
        $this->assessment = $assessment;

        return $this;
    }

    /**
     * Get assessment
     *
     * @return \Getunik\BleedHd\AssessmentDataBundle\Entity\Assessment
     */
    public function getAssessment()
    {
        return $this->assessment;
    }
}
