<?php

namespace App\Security;

use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof Utilisateur) {
            return;
        }

        if (!$user->isEstActif()) {
            throw new CustomUserMessageAccountStatusException(
                'Votre compte est désactivé. Contactez un administrateur.'
            );
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}