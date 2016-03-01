<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use FOS\UserBundle\Model\User;
use Getunik\BleedHd\AssessmentDataBundle\Entity\OwnerInterface;


/**
 * The resource owner voter can be used to determine if a user is the owner of the given
 * resource or not in order to limit access. For example in a @Security attribute, this
 * voter can be used with the following expression "is_granted('isOwner', patient)".
 */
class ResourceOwnerVoter extends Voter
{
	const IS_OWNER = 'isOwner';

	protected function supports($attribute, $subject)
	{
		if ($attribute !== self::IS_OWNER)
		{
			return false;
		}

		if (!$subject instanceof OwnerInterface) {
			return false;
		}

		return true;
	}

	protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
	{
		$user = $token->getUser();

		if (!$user instanceof User) {
			// the user must be logged in; if not, deny access
			return false;
		}

		$owner = $subject->getCreatedBy();

		// if the current user is the creator (owner) of the subject, then access is granted
		return $user->getId() == $owner;
	}
}
