<?php

namespace LesPlates\Permanences\Tests\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use LesPlates\Permanences\Domain\Agenda;
use LesPlates\Permanences\Domain\Benevole;
use LesPlates\Permanences\Domain\Engagement;
use LesPlates\Permanences\Domain\Exception\EngagementEstUnDoublonException;
use LesPlates\Permanences\Domain\Exception\EngagementPourJourneeInconnueException;
use LesPlates\Permanences\Domain\JourneeAgenda;
use LesPlates\Permanences\Domain\Periode;
use PHPUnit\Framework\TestCase;

class JourneeAgendaTest extends TestCase
{
    public function test_une_journee_peut_avoir_des_engagements_sur_ses_creneaux()
    {
        $benevole = $this->createMock(Benevole::class);
        $journee=new JourneeAgenda('1973','09:00','19:00');
        $engagement=new Engagement($journee,'09:00','14:00',$benevole);
        $engagement2=new Engagement($journee,'14:00','19:00',$benevole);
        $journee->inscrire($engagement);
        $journee->inscrire($engagement2);
        $this->assertEquals($engagement,$journee->engagementCreneau1());
        $this->assertEquals($engagement2,$journee->engagementCreneau2());
    }
    public function test_une_journee_ne_peut_pas_avoir_deux_engagements_identiques_pour_un_benevole()
    {
        $benevole = $this->createMock(Benevole::class);
        $this->expectException(EngagementEstUnDoublonException::class);
        $journee=new JourneeAgenda('1973','09:00','19:00');
        $engagement=new Engagement($journee,'09:00','14:00',$benevole);
        $engagement2=new Engagement($journee,'09:00','14:00',$benevole);
        $journee->inscrire($engagement);
        $journee->inscrire($engagement2);
    }
    public function test_deux_bénévoles_différents_peuvent_s‘inscrire_pour_le_même_créneau()
    {
        $benevole = $this->createMock(Benevole::class);
        $benevole2 = $this->createMock(Benevole::class);
        $journee=new JourneeAgenda('1973','09:00','19:00');
        $engagement=new Engagement($journee,'09:00','14:00',$benevole);
        $engagement2=new Engagement($journee,'09:00','14:00',$benevole2);
        $journee->inscrire($engagement);
        $journee->inscrire($engagement2);
        $this->assertCount(2,$journee->engagements);
    }
}
