<?php

namespace Getunik\BleedHd\AssessmentUIBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GetunikBleedHdAssessmentUIBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
