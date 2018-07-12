<?php

namespace LesPlates\Permanences\Tests\Domain;

use LesPlates\Permanences\Domain\Engagement;
use LesPlates\Permanences\Domain\Journee;
use LesPlates\Permanences\Domain\Benevole;
use LesPlates\Permanences\Domain\Exception\EngagementNonComprisDansJourneeException;
use LesPlates\Permanences\Domain\Exception\EngagementEstPasUnCreneauException;
use PHPUnit\Framework\TestCase;

class EngagementTest extends TestCase
{
    public function test_un_engagement_a_une_journee_et_des_horaires()
    {
        $journee = $this->createMock(Journee::class);
        $benevole = $this->createMock(Benevole::class);
        $journee->method('date')->willReturn('1973-06-13');
        $journee->method('contientCreneau')->willReturn(true);
        $journee->method('contient')->willReturn(true);
        $debut = "07:00";
        $fin = "08:00";
        $engagement = new Engagement($journee, $debut, $fin, $benevole);
        $this->assertEquals("1973-06-13", $engagement->date());
        $this->assertEquals("07:00", $engagement->heureDebut());
        $this->assertEquals("08:00", $engagement->heureFin());

    }

    public function test_un_engagement_doit_etre_dans_les_horaires_d_une_journee()
    {
        $this->expectException(EngagementNonComprisDansJourneeException::class);

        $benevole = $this->createMock(Benevole::class);
        $journee = new Journee("1973-03-13", "10:00", "16:00");
        $debut = "09:00";
        $fin = "13:00";
        $engagement = new Engagement($journee, $debut, $fin, $benevole);
    }
    public function test_on_ne_peut_pas_peut_s‘engager_pour_un_creneau_non_reconnu()
    {
        $this->expectException(EngagementEstPasUnCreneauException::class);
        $benevole = $this->createMock(Benevole::class);
        $journee = new Journee("1973-03-13", "10:00", "16:00");
        $debut = "10:00";
        $fin = "12:00";
        $engagement = new Engagement($journee, $debut, $fin, $benevole);
    }

    public function test_un_engagement_concerne_un_benevole()
    {
        $journee = new Journee("1973-03-13", "10:00", "16:00");
        $benevole = $this->createMock(Benevole::class);
        $engagement = new Engagement($journee, "10:00", "13:00", $benevole);
        $this->assertEquals($engagement->benevole(), $benevole);
    }
}
