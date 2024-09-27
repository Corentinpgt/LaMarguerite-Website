<?php
//----------------------------------------------------------------------
// src/Security/BlogVoter.php
//----------------------------------------------------------------------

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use App\Entity\Access;
use App\Entity\Blog\Article;

class BlogVoter extends Voter
{
	//--------------------------------------------------------------------------------
	const DASHBOARD = "blog_dashboard";
	const ADD_ARTICLE = "blog_article_add";
	const VIEW_ARTICLE = "blog_article_view";
	const EDIT_ARTICLE = "blog_article_edit";
	const PUBLISH_ARTICLE = "blog_article_publish";
	const DELETE_ARTICLE = "blog_article_delete";
	//--------------------------------------------------------------------------------
	const PERMISSIONS = array(
		self::DASHBOARD,
		self::ADD_ARTICLE,
		self::VIEW_ARTICLE,
		self::EDIT_ARTICLE,
		self::PUBLISH_ARTICLE,
		self::DELETE_ARTICLE,
	);
	//--------------------------------------------------------------------------------

	protected function supports(string $attribute, $subject): bool
	{
		// if the attribute isn't one we support, return false
		if (!in_array($attribute, self::PERMISSIONS))
		{
			return false;
		}

		// Only vote on Article objects (if subject is not null)
		if ($subject !== null && !$subject instanceof Article)
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
			case self::ADD_ARTICLE:
				return $this->canAddArticle($user);
			case self::VIEW_ARTICLE:
				return $this->canViewArticle($user, $subject);
			case self::EDIT_ARTICLE:
				return $this->canEditArticle($user, $subject);
			case self::PUBLISH_ARTICLE:
				return $this->canPublishArticle($user, $subject);
			case self::DELETE_ARTICLE:
				return $this->canDeleteArticle($user, $subject);
		}

		throw new \LogicException('This code should not be reached!');
	}

	private function canAccessDashboard(Access $user): bool
	{
		return true;
	}

	private function canAddArticle(Access $user): bool
	{
		return true;
	}

	private function canViewArticle(Access $user, Article $subject): bool
	{
		return true;
	}

	private function canEditArticle(Access $user, Article $subject): bool
	{
		return true;
	}

	private function canPublishArticle(Access $user, Article $subject): bool
	{
		return true;
	}

	private function canDeleteArticle(Access $user, Article $subject): bool
	{
		return true;
	}
}
