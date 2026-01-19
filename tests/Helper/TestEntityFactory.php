<?php

namespace App\Tests\Helper;

use App\Entity\Categorie;
use App\Entity\Utilisateur;
use DateTimeImmutable;
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
    public static function createCategorie(EntityManagerInterface $entityManagerInterface, ?string $nom = null, ?string $slug = null):Categorie{
        $categorie = new Categorie();
        $categorie->setNom($nom ?? ('Cat '.uniqid()));
        $categorie->setSlug($slug ?? ('cat_'.uniqid()));
        if (method_exists($categorie,'setCreatedAt')) {
            $categorie->setCreatedAt(new DateTimeImmutable());
        }
        $entityManagerInterface->persist($categorie);
        $entityManagerInterface->flush();
        return $categorie;
    }
}
