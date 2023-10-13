<?php

namespace App\Security\Voter\Movie;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use function str_starts_with;

class IsAdminVoter implements VoterInterface
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $abstain = false;

        foreach ($attributes as $attribute) {
            if (str_starts_with($attribute, 'ROLE_') === false) {
                continue;
            }

            $abstain = true;
            break;
        }

        if (true === $abstain) {
            return self::ACCESS_ABSTAIN;
        }

        return $this->authorizationChecker->isGranted('ROLE_ADMIN') ? self::ACCESS_GRANTED : self::ACCESS_ABSTAIN;
    }
}
