<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Entity;


interface UpdateInformationInterface
{
	public function setLastUpdatedDate($lastUpdatedDate);
	public function setLastUpdatedBy($lastUpdatedBy);
}
