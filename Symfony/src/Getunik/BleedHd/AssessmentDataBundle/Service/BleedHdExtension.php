<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Service;


class BleedHdExtension extends \Twig_Extension
{
    protected $version;

    public function __construct(VersionService $version)
    {
        $this->version = $version;
    }

    public function getGlobals()
    {
        return array(
            'app_version_int' => $this->version->getVersion(true),
            'app_version_ext' => $this->version->getVersion(false),
        );
    }

    public function getName()
    {
        return 'bleed_hd_extension';
    }
}
