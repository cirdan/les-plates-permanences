<?php

namespace LesPlates\Permanences\Tests\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use LesPlates\Permanences\Domain\Agenda;
use LesPlates\Permanences\Domain\Engagement;
use LesPlates\Permanences\Domain\Exception\EngagementPourJourneeInconnueException;
use LesPlates\Permanences\Domain\Journee;
use LesPlates\Permanences\Domain\Periode;
use PHPUnit\Framework\TestCase;

class AgendaTest extends TestCase
{
    public function test_un_agenda_concerne_des_journees()
    {
        $periode=$this->createMock(Periode::class);
        $journee=$this->createMock(Journee::class);
        $journee->method('date')->willReturn('1973-06-14');
        $journee->method('heureDebut')->willReturn('09:30');
        $journee->method('heureFin')->willReturn('18:00');
        $periode->method('listeJournees')->willReturn(new ArrayCollection([$journee]));
        $agenda=new Agenda($periode);
        $this->assertEquals(count($periode->listeJournees()),
            count($agenda->listeJournees()));
    }
    public function test_on_peut_ajouter_un_engagement_a_un_agenda()
    {
        $periode=$this->createMock(Periode::class);
        $journee=$this->createMock(Journee::class);
        $journee->method('date')->willReturn('1973-06-14');
        $journee->method('heureDebut')->willReturn('09:30');
        $journee->method('heureFin')->willReturn('18:00');
        $periode->method('listeJournees')->willReturn(new ArrayCollection([$journee]));
        $engagement=$this->createMock(Engagement::class);
        $engagement->method('date')->willReturn('1973-06-14');
        $agenda=new Agenda($periode);
        $agenda->inscrire($engagement);
        $engagements=new ArrayCollection([$engagement]);
        $this->assertEquals($engagements, $agenda->listeEngagements());
    }

    public function test_on_ne_peut_pas_ajouter_un_engagement_a_un_agenda_si_la_journee_n_est_pas_dans_sa_periode()
    {
        $this->expectException(EngagementPourJourneeInconnueException::class);
        $periode=$this->createMock(Periode::class);
        $journee=$this->createMock(Journee::class);
        $journee->method('heureDebut')->willReturn('09:30');
        $journee->method('heureFin')->willReturn('18:00');
        $journee->method('date')->willReturn('1973-06-13');
        $periode->method('listeJournees')->willReturn(new ArrayCollection([$journee]));
        $agenda=new Agenda($periode);
        $engagement=$this->createMock(Engagement::class);
        $engagement->method('date')->willReturn('1973-06-14');
        $agenda->inscrire($engagement);

    }

}
