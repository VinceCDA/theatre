<?php

namespace App\Tests;

use Doctrine\ORM\Query\Expr\Func;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityAccessTest extends WebTestCase
{
    public function testAccessNewEvent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/evenement/new');
        $this->assertResponseRedirects('/login');
    }

    public function testAccessNewCategorie()
    {
        $client = static::createClient();
        $client->request('GET', '/categorie/new');
        $this->assertResponseRedirects('/login');
    }
}
