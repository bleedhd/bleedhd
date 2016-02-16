<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use FOS\UserBundle\Model\User;
use Getunik\BleedHd\AssessmentDataBundle\Entity\OwnerInterface;


/**
 * The resource owner voter can be used to determine if a user is the owner of the given
 * resource or not in order to limit access. For example in a @Security attribute, this
 * voter can be used with the following expression "is_granted('isOwner', patient)".
 */
class ResourceOwnerVoter implements VoterInterface
{
	const IS_OWNER = 'isOwner';

	public function supportsAttribute($attribute) {
		// if the attribute isn't one we support, return false
		return $attribute == 'isOwner';
	}

	public function supportsClass($class)
	{
		// voter supports all type of token classes, so return true
		return true;
	}

	public function vote(TokenInterface $token, $subject, array $attributes)
	{
		foreach ($attributes as $attribute) {
			if (!$this->supportsAttribute($attribute)) {
				continue;
			}

			$user = $token->getUser();

			if (!$user instanceof User) {
				// the user must be logged in; if not, deny access
				return VoterInterface::ACCESS_DENIED;
			}

			if (!$subject instanceof OwnerInterface) {
				return VoterInterface::ACCESS_DENIED;
			}

			$owner = $subject->getCreatedBy();

			// if the current user is the creator (owner) of the subject, then access is granted
			return $user->getId() == $owner ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
		}

		return VoterInterface::ACCESS_ABSTAIN;
	}
}
