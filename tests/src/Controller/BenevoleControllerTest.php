<?php

namespace LesPlates\Permanences\Controller;

use LesPlates\Permanences\Domain\Benevole;
use LesPlates\Permanences\Domain\Exception\BenevoleContactException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;


class BenevoleControllerTest extends WebTestCase
{
    public function test_on_ne_peut_pas_activer_les_notifications_sans_benevole()
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/benevole/activer-notifications');
        $this->assertEquals(500, $client->getResponse()->getStatusCode());

    }
    public function test_activer_les_notifications_si_on_dispose_d‘un_moyen_de_contact_est_effectif()
    {
        $kernel=self::bootKernel();
        $this->benevole=new Benevole();
        $this->benevole->ajouterEmail("sylvain@akenlab.fr");
        $this->entityManager =  $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->entityManager
            ->getRepository(Benevole::class)
            ->save($this->benevole);

        $client = static::createClient();
        $client->followRedirects();

        $this->cookie = new Cookie('benevole_id', $this->benevole->id(), time() + 3600 * 24 * 60, '/', "", false, false);
        $client->getCookieJar()->set( $this->cookie );
        $client->request('GET', '/');
        $crawler = $client->request('GET', '/benevole/activer-notifications');
        $this->assertEquals(
            1,
            $crawler->filter('html:contains("Notifications activées")')->count()
        );
    }

}
