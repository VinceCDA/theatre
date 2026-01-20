<?php

namespace App\Tests;

use App\Entity\Evenement;
use App\Tests\Helper\TestEntityFactory;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use function PHPUnit\Framework\assertSame;

class EvenementTest extends WebTestCase
{
    public function testLoggedUserCanCreateEvenement(): void
    {
        $client = static::createClient();
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $user = TestEntityFactory::createUser($em, $hasher);
        $categorie1 = TestEntityFactory::createCategorie($em);
        $categorie2 = TestEntityFactory::createCategorie($em);
        $client->loginUser($user);
        $client->request('GET', '/evenement/new');
        $this->assertResponseIsSuccessful();
        $titre = 'Titre' . uniqid();
        $description = 'Description' . uniqid();
        $lieu = 'Paris';
        $capacite = 10;
        $dateDebut = new DateTimeImmutable('2022-12-23 12:20');
        $dateFin = new DateTimeImmutable('2025-02-15 14:23');
        $crawler = $client->request('GET', '/evenement/new');
        $token = $crawler->filter('#evenement__token')->attr('value');
        // $client->submitForm('Save', [
        //     'evenement[Titre]' => $titre,
        //     'evenement[Description]' => $description,
        //     'evenement[Lieu]' => $lieu,
        //     'evenement[Capacite]' => $capacite,
        //     'evenement[DateDebut]' => $dateDebut->format("Y-m-d\TH:i"),
        //     'evenement[DateFin]' => $dateFin->format("Y-m-d\TH:i"),
        //     'evenement[Categories]' => [$categorie1->getId(), $categorie2->getId()]
        // ]);
        // $this->assertResponseRedirects('/evenement', 303);
        // $repo = $em->getRepository(Evenement::class);
        // $evenement = $repo->findOneBy(['Titre' => $titre]);
        // self::assertNotNull($evenement);
        // self::assertSame($description, $evenement->getDescription());
        $client->request('POST', '/evenement/new', [
            'evenement' => [
                'Titre' => $titre,
                'Description' => 'Description test',
                'DateDebut' => $dateDebut->format("Y-m-d\TH:i"),
                'DateFin' => $dateFin->format("Y-m-d\TH:i"),
                'Lieu' => 'salle 1',
                'Capacite' => '20',
                'Categories' => [
                    (string) $categorie1->getId(),
                    (string) $categorie2->getId()
                ],
                '_token' => $token,
            ],
        ]);
        $this->assertResponseRedirects('/evenement');
        $repo = $em->getRepository(Evenement::class);
        $even = $repo->findOneBy(['Titre' => $titre]);
        self::assertNotNull($even);
        self::assertSame($user->getId(), $even->getAuteur()->getId());
        $tab = [];
        foreach ($even->getCategories() as $value) {
            array_push($tab, $value->getId());
        }
        $tabCat = [$categorie2->getId(), $categorie1->getId()];
        $tabDiff = array_diff($tab, $tabCat);
        dump($tabDiff);
        self::assertEmpty($tabDiff);
    }
}
