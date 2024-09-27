<?php
//----------------------------------------------------------------------
// src/Security/SimpleVoter.php
//----------------------------------------------------------------------

namespace App\Security;

use App\Entity\Access;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SimpleVoter extends Voter
{
	//--------------------------------------------------------------------------------
	const OPEN_SESAME = "open_sesame";
	//--------------------------------------------------------------------------------
	const ACCESS_COMMONPLACE = "access_commonplace";
	const ACCESS_BLUEPRINT = "access_blueprint";
	const ACCESS_BUDGET = "access_budget";
	const ACCESS_KEEP = "access_keep";
	const ACCESS_READING = "access_reading";
	const ACCESS_TRACKING = "access_tracking";
	const ACCESS_VEHICLE = "access_vehicle";
	//--------------------------------------------------------------------------------
	const ACCESS_GLOBALS = array(
		self::OPEN_SESAME,

		self::ACCESS_COMMONPLACE,
		self::ACCESS_BLUEPRINT,
		self::ACCESS_BUDGET,
		self::ACCESS_KEEP,
		self::ACCESS_READING,
		self::ACCESS_TRACKING,
		self::ACCESS_VEHICLE,
	);
	//--------------------------------------------------------------------------------

	protected function supports(string $attribute, $subject): bool
	{
		// if the attribute isn't one we support, return false
		if (!in_array($attribute, self::ACCESS_GLOBALS))
		{
			return false;
		}

		// only vote on Post objects
		// if (!$subject instanceof Post)
		// {
		// 	return false;
		// }

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
		if ($subject !== null)
		{
			$owner = $subject->getOwner();
			if ($owner === null)
				return false;
			if (!$owner->equals($user))
				return false;
		}

		switch ($attribute)
		{
			case self::OPEN_SESAME:
				return $this->canOpenSesame($user);

			case self::ACCESS_COMMONPLACE:
				return $this->canAccessCommonplace($user, $subject);
			case self::ACCESS_BLUEPRINT:
				return $this->canAccessBlueprint($user, $subject);
			case self::ACCESS_BUDGET:
				return $this->canAccessBudget($user, $subject);
			case self::ACCESS_KEEP:
				return $this->canAccessKeep($user, $subject);
			case self::ACCESS_READING:
				return $this->canAccessReading($user, $subject);
			case self::ACCESS_TRACKING:
				return $this->canAccessTracking($user, $subject);
			case self::ACCESS_VEHICLE:
				return $this->canAccessVehicle($user, $subject);
		}

		throw new \LogicException('This code should not be reached!');
	}

	private function canOpenSesame(Access $user): bool
	{
		if ($user->getId() === 2)
		{
			return true;
		}
		return false;
	}

	private function canAccessCommonplace(Access $user, $subject): bool
	{
		return true;
	}

	private function canAccessBlueprint(Access $user, $subject): bool
	{
		return true;
	}

	private function canAccessBudget(Access $user, $subject): bool
	{
		return true;
	}

	private function canAccessKeep(Access $user, $subject): bool
	{
		return true;
	}

	private function canAccessReading(Access $user, $subject): bool
	{
		return true;
	}

	private function canAccessTracking(Access $user, $subject): bool
	{
		return true;
	}

	private function canAccessVehicle(Access $user, $subject): bool
	{
		return true;
	}
}
