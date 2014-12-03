<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Entity;


interface AuditableEntityInterface
{
	public function setLastUpdatedDate($lastUpdatedDate);
	public function setLastUpdatedBy($lastUpdatedBy);
}
