<?php

namespace LesPlates\Permanences\Controller;

use LesPlates\Permanences\Domain\Benevole;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;


class EngagementControllerTest extends WebTestCase
{
    private $cookie;
    private $entityManager;

    public function test_on_peut_s‘engager_pour_un_creneau()
    {
        $client = static::createClient();
        $client->getCookieJar()->set( $this->cookie );

        $crawler = $client->request('GET', '/');
        $nbBoutons=$crawler->filter('a.bouton.creneau-needed')->count();
        $link = $crawler
            ->filter('a.bouton.creneau-needed')
            ->eq(0) // select the second link in the list
            ->link();
        $crawler = $client->click($link);
        $this->assertEquals(
            $nbBoutons-1,
            $client->request('GET', '/')->filter('a.bouton.creneau-needed')->count()
        );
    }
    public function test_s‘engager_necessite_de_donnner_un_nom()
    {
        $client = static::createClient();
//        $client->getCookieJar()->set( $this->cookie );
        $client->followRedirects();

        $crawler = $client->request('GET', '/');
        $link = $crawler
            ->filter('a.bouton.creneau-needed')
            ->eq(0) // select the first link in the list
            ->link();
        $crawler = $client->click($link);
        $this->assertEquals(
            1,
            $crawler->filter('html:contains("il nous faut ton nom")')->count()
        );
    }
    public function test_on_peut_annuler_un_engagement_pour_un_creneau()
    {
        $client = static::createClient();
        $client->getCookieJar()->set( $this->cookie );

        $crawler = $client->request('GET', '/');
        $nbBoutons=$crawler->filter('a.bouton.creneau-me')->count();
        $link = $crawler
            ->filter('a.bouton.creneau-me')
            ->eq(0) // select the second link in the list
            ->link();
        $crawler = $client->click($link);
        $this->assertEquals(
            $nbBoutons-1,
            $client->request('GET', '/')->filter('a.bouton.creneau-me')->count()
        );
    }
    public function test_si_il_y_a_un_cookie_le_benevole_est_reconnu()
    {
        $client = static::createClient();
        $client->getCookieJar()->set( $this->cookie );

        $crawler = $client->request('GET', '/');
        $this->assertEquals(
            1,
            $crawler->filter('html:contains("Gérer mes notifications")')->count()
        );
    }

    protected function setUp()
    {
        $kernel=self::bootKernel();
        $this->benevole=new Benevole();
        $this->benevole->enregistrerNom("John Doe");
        $this->entityManager =  $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->entityManager
            ->getRepository(Benevole::class)
            ->save($this->benevole);
        $client = static::createClient();


        $this->cookie = new Cookie('benevole_id', $this->benevole->id(), time() + 3600 * 24 * 60, '/', "", false, false);
        $client->getCookieJar()->set( $this->cookie );

        $crawler = $client->request('GET', '/');
        $link = $crawler
            ->filter('a.bouton.creneau-needed')
            ->eq(0) // select the first link in the list
            ->link();
        $client->click($link);
    }


}
