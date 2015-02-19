<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Getunik\BleedHd\SecurityBundle\EventListener;

use Symfony\Component\Security\Http\Logout\DefaultLogoutSuccessHandler;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Getunik\BleedHd\SecurityBundle\Service\OAuthHelper;


/**
 * This handler clears the passed cookies when a user logs out.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class TokenRemoveLogoutHandler extends DefaultLogoutSuccessHandler
{
    protected $helper;

    /**
     * Constructor.
     *
     * @param array $cookies An array of cookie names to unset
     */
    public function __construct($httpUtils, $targetUrl = '/', OAuthHelper $helper)
    {
        parent::__construct($httpUtils, $targetUrl);
        $this->helper = $helper;
    }

    public function onLogoutSuccess(Request $request)
    {
        $this->helper->disableToken();

        $targetUrl = $this->targetUrl;

        if ($loginRedirectTarget = $request->get('_target_path', null)) {
            if (!empty($loginRedirectTarget)) {
                $targetUrl = $targetUrl . (strpos($targetUrl, '?') === FALSE ? '?' : '&') . '_target_path=' . $loginRedirectTarget;
            }
        }

        return new RedirectResponse($targetUrl);
    }
}
