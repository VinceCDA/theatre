<?php

namespace App\Tests\Helper;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class TestEntityFactory
{
    public static function createUser(EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $userPasswordHasherInterface, ?string $email = null, string $plainPassword = "pass_1234"): Utilisateur
    {
        $user = new Utilisateur();
        $user->setEmail($email ?? ('user' . uniqid() . '@test.local'));
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($userPasswordHasherInterface->hashPassword($user, $plainPassword));
        $entityManagerInterface->persist($user);
        $entityManagerInterface->flush();
        return $user;
    }
}
