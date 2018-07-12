<?php

namespace LesPlates\Permanences\Tests\Domain;

use LesPlates\Permanences\Domain\Journee;
use LesPlates\Permanences\Domain\Engagement;
use LesPlates\Permanences\Infrastructure\InMemoryEngagementRepository as EngagementRepository;
use PHPUnit\Framework\TestCase;

class InMemoryEngagementRepositoryTest extends TestCase
{
    public function test_on_peut_ajouter_et_supprimer_un_engagement()
    {
        $journee=$this->createMock(Journee::class);
        $journee->method("date")->willReturn("1973-06-13");
        $journee->method("heureDebut")->willReturn("10:00");
        $journee->method("heureFin")->willReturn("18:00");
        $engagement=$this->createMock(Engagement::class);
        $engagement->method("date")->willReturn("1973-06-13");
        $engagement->method("heureDebut")->willReturn("10:00");
        $engagement->method("heureFin")->willReturn("14:00");
        $repo = new EngagementRepository();
        $nbEngagementsAvant=count($repo->findAll());
        $repo->save($engagement);
        $this->assertEquals($nbEngagementsAvant+1, count($repo->findAll()));
        $repo->remove($engagement);
        $this->assertEquals($nbEngagementsAvant, count($repo->findAll()));
    }
}