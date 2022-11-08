<?php

namespace App\DataFixtures\Providers;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HashPasswordProvider
{
    public function __construct(private readonly UserPasswordHasherInterface $encoder)
    {
    }

    public function hashPassword(User $user, string $plainPassword): string
    {
        // hash the password (based on the security.yaml config for the $user class)
        return $this->encoder->hashPassword(
            $user,
            $plainPassword
        );
    }

}