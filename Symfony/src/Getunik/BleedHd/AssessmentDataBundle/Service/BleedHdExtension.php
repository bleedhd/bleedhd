<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;


class BleedHdExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    protected $version;
    protected $session;

    public function __construct(VersionService $version, SessionInterface $session)
    {
        $this->version = $version;
        $this->session = $session;
    }

    public function getGlobals()
    {
        $token = $this->session->get('getunik_bleed_hd_security.oauth_token');
        return array(
            'app_version_int' => $this->version->getVersion(true),
            'app_version_ext' => $this->version->getVersion(false),
            'currentUid' => ($token === NULL ? -1 : $token->uid),
        );
    }

    public function getName()
    {
        return 'bleed_hd_extension';
    }
}
