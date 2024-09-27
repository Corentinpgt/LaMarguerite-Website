<?php
//----------------------------------------------------------------------
// src/Security/UserVoter.php
//----------------------------------------------------------------------

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use App\Entity\Access;

class UserVoter extends Voter
{
	//--------------------------------------------------------------------------------
	const DASHBOARD = "user_dashboard";
	const ADD = "user_add";
	const EDIT = "user_edit";
	const DELETE = "user_delete";
	//--------------------------------------------------------------------------------
	const PERMISSIONS = array(
		self::DASHBOARD,
		self::ADD,
		self::EDIT,
		self::DELETE,
	);
	//--------------------------------------------------------------------------------

	protected function supports(string $attribute, $subject): bool
	{
		// if the attribute isn't one we support, return false
		if (!in_array($attribute, self::PERMISSIONS))
		{
			return false;
		}

		// Only vote on Access objects (if subject is not null)
		if ($subject !== null && !$subject instanceof Access)
		{
			return false;
		}

		return true;
	}

	protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
	{
		$user = $token->getUser();

		if (!$user instanceof Access)
		{
			// the user must be logged in; if not, deny access
			return false;
		}

		// The user must be the owner of the object
		// if ($subject !== null)
		// {
		// 	$owner = $subject->getOwner();
		// 	if ($owner === null)
		// 		return false;
		// 	if (!$owner->equals($user))
		// 		return false;
		// }

		switch ($attribute)
		{
			case self::DASHBOARD:
				return $this->canAccessDashboard($user);
			case self::ADD:
				return $this->canAdd($user);
			case self::EDIT:
				return $this->canEdit($user, $subject);
			case self::DELETE:
				return $this->canDelete($user, $subject);
		}

		throw new \LogicException('This code should not be reached!');
	}

	private function canAccessDashboard(Access $user): bool
	{
		return true;
	}

	private function canAdd(Access $user): bool
	{
		return true;
	}

	private function canEdit(Access $user, Access $subject): bool
	{
		return true;
	}

	private function canDelete(Access $user, Access $subject): bool
	{
		return true;
	}
}
