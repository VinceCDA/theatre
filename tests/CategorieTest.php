<?php

namespace App\Tests;

use App\Entity\Categorie;
use App\Tests\Helper\TestEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CategorieTest extends WebTestCase
{
    public function testLoggedUserCanCreateCategory(): void
    {
        $client = static::createClient();
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $user = TestEntityFactory::createUser($em, $hasher);
        $client->loginUser($user);
        $client->request('GET', '/categorie/new');
        $this->assertResponseIsSuccessful();
        $nom = 'Comédie' . uniqid();
        $slug = 'comedie' . uniqid();
        $client->submitForm('Save', [
            'categorie[Nom]' => $nom,
            'categorie[Slug]' => $slug
        ]);
        $this->assertResponseRedirects('/categorie', 303);
        $repo = $em->getRepository(Categorie::class);
        $cat = $repo->findOneBy(['Slug' => $slug]);
        self::assertNotNull($cat);
        self::assertSame($nom, $cat->getNom());
    }
}
