<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Entity;


interface CreationInformationInterface
{
	public function setCreatedDate($lastUpdatedDate);
	public function setCreatedBy($lastUpdatedBy);
}
