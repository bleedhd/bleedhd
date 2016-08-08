<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Listener;


use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Getunik\BleedHd\AssessmentDataBundle\Annotation\FeatureRequire;
use Getunik\BleedHd\AssessmentDataBundle\Exception\FeatureUnavailableException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;


/**
 * Class FeatureRequireListener
 * @package Getunik\BleedHd\AssessmentDataBundle\Listener
 *
 * see http://php-and-symfony.matthiasnoback.nl/2012/12/prevent-controller-execution-with-annotations-and-return-a-custom-response/
 */
class FeatureRequireListener
{
	private $annotationReader;
	private $settings;

	public function __construct(Reader $annotationReader, array $bleedHdSettings)
	{
		$this->annotationReader = $annotationReader;
		$this->settings = $bleedHdSettings;
	}

	public function onFilterController(FilterControllerEvent $event)
	{
		$controller = $event->getController();

		list($object, $method) = $controller;

		// the controller could be a proxy, e.g. when using the JMSSecuriyExtraBundle or JMSDiExtraBundle
		$className = ClassUtils::getClass($object);

		$reflectionClass = new \ReflectionClass($className);
		$reflectionMethod = $reflectionClass->getMethod($method);

		$allAnnotations = $this->annotationReader->getClassAnnotations($reflectionClass);
		$allAnnotations = array_merge($allAnnotations, $this->annotationReader->getMethodAnnotations($reflectionMethod));

		$featureAnnotations = array_filter($allAnnotations, function($annotation) {
			return $annotation instanceof FeatureRequire;
		});

		foreach ($featureAnnotations as $featureAnnotation) {
			/** @var FeatureRequire $featureAnnotation */
			$feature = $featureAnnotation->getFeature();

			if (!(isset($this->settings['feature'][$feature]) && $this->settings['feature'][$feature] === true)) {
				throw new FeatureUnavailableException('Feature "' . $feature . '" is not available');
			}
		}
	}
}
