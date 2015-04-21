<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\UserBundle\Model\UserManager;
use Getunik\BleedHd\SecurityBundle\Entity\User;


/**
 * PatientsController
 */
class UsersController extends FOSRestController
{
    protected $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @Security("has_role('ROLE_READER')")
     */
    public function getUsersAction()
    {
        $users = $this->userManager->findUsers();
        array_walk($users, array('Getunik\BleedHd\AssessmentDataBundle\Controller\UsersController', 'mapUser'));
        return $this->handleView($this->view($users));
    }

    /**
     * @Security("has_role('ROLE_READER')")
     * @ParamConverter("user", options={"id" = "user"})
     */
    public function getUserAction(User $user)
    {
        return $this->handleView($this->view(UsersController::mapUser($user)));
    }

    protected static function mapUser(User &$item)
    {
        $item = array(
            'id' => $item->getId(),
            'username' => $item->getUsername(),
            'email' => $item->getEmail(),
            'full_name' => $item->getFullName(),
        );

        return $item;
    }
}
