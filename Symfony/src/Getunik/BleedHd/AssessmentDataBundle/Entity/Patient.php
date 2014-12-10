<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Patient
 */
class Patient implements UpdateInformationInterface
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
     * @var boolean
     */
    private $isActive;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var \DateTime
     */
    private $birthdate;

    /**
     * @var string
     */
    private $sex;

    /**
     * @var string
     */
    private $patientNumber;

    /**
     * @var string
     */
    private $upn;

    /**
     * @var string
     */
    private $diagnosis;

    /**
     * @var \DateTime
     */
    private $diagnosisDate;

    /**
     * @var string
     */
    private $patientBloodType;

    /**
     * @var string
     */
    private $donorBloodType;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $statuses;

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
     * @return Patient
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
     * @return Patient
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return Patient
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Patient
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Patient
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return Patient
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set sex
     *
     * @param string $sex
     * @return Patient
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set patientNumber
     *
     * @param string $patientNumber
     * @return Patient
     */
    public function setPatientNumber($patientNumber)
    {
        $this->patientNumber = $patientNumber;

        return $this;
    }

    /**
     * Get patientNumber
     *
     * @return string
     */
    public function getPatientNumber()
    {
        return $this->patientNumber;
    }

    /**
     * Set upn
     *
     * @param string $upn
     * @return Patient
     */
    public function setUpn($upn)
    {
        $this->upn = $upn;

        return $this;
    }

    /**
     * Get upn
     *
     * @return string
     */
    public function getUpn()
    {
        return $this->upn;
    }

    /**
     * Set diagnosis
     *
     * @param string $diagnosis
     * @return Patient
     */
    public function setDiagnosis($diagnosis)
    {
        $this->diagnosis = $diagnosis;

        return $this;
    }

    /**
     * Get diagnosis
     *
     * @return string
     */
    public function getDiagnosis()
    {
        return $this->diagnosis;
    }

    /**
     * Set diagnosisDate
     *
     * @param \DateTime $diagnosisDate
     * @return Patient
     */
    public function setDiagnosisDate($diagnosisDate)
    {
        $this->diagnosisDate = $diagnosisDate;

        return $this;
    }

    /**
     * Get diagnosisDate
     *
     * @return \DateTime
     */
    public function getDiagnosisDate()
    {
        return $this->diagnosisDate;
    }

    /**
     * Set patientBloodType
     *
     * @param string $patientBloodType
     * @return Patient
     */
    public function setPatientBloodType($patientBloodType)
    {
        $this->patientBloodType = $patientBloodType;

        return $this;
    }

    /**
     * Get patientBloodType
     *
     * @return string
     */
    public function getPatientBloodType()
    {
        return $this->patientBloodType;
    }

    /**
     * Set donorBloodType
     *
     * @param string $donorBloodType
     * @return Patient
     */
    public function setDonorBloodType($donorBloodType)
    {
        $this->donorBloodType = $donorBloodType;

        return $this;
    }

    /**
     * Get donorBloodType
     *
     * @return string
     */
    public function getDonorBloodType()
    {
        return $this->donorBloodType;
    }

    /**
     * Add statuses
     *
     * @param \Getunik\BleedHd\AssessmentDataBundle\Entity\PatientStatus $statuses
     * @return Patient
     */
    public function addStatus(\Getunik\BleedHd\AssessmentDataBundle\Entity\PatientStatus $statuses)
    {
        $this->statuses[] = $statuses;

        return $this;
    }

    /**
     * Remove statuses
     *
     * @param \Getunik\BleedHd\AssessmentDataBundle\Entity\PatientStatus $statuses
     */
    public function removeStatus(\Getunik\BleedHd\AssessmentDataBundle\Entity\PatientStatus $statuses)
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
}