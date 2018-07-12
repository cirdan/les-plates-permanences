<?php

namespace LesPlates\Permanences\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class AgendaControllerTest extends WebTestCase
{
    public function test_on_voit_des_journees_sur_la_home()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.journee')->count()
        );
    }
}
