<?php


namespace Getunik\BleedHd\AssessmentDataBundle\Export;


use Getunik\BleedHd\AssessmentDataBundle\Entity\Response;

class ResponseValue
{
	/**
	 * @var array
	 */
	private $result;

	public function __construct(Response $response)
	{
		$this->result = $response->getResult();
	}

	public function __toString()
	{
		if (isset($this->result['data']) && is_array($this->result['data']) && isset($this->result['data']['value'])) {
			$value = $this->result['data']['value'];
			return is_array($value) ? json_encode($value) : (string) $value;
		}

		return '';
	}
}
