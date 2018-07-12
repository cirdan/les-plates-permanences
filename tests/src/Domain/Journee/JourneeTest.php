<?php

namespace LesPlates\Permanences\Tests\Domain;

use LesPlates\Permanences\Domain\Journee;
use LesPlates\Permanences\Domain\Periode;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\DateTime;

class JourneeTest extends TestCase
{
    public function test_une_journee_a_une_date_et_des_horaires()
    {
        $date="1973-06-13";
        $debut="09:30";
        $fin="18:00";
        $journee=new Journee($date,$debut,$fin);
        $this->assertEquals("1973-06-13", $journee->date());
        $this->assertEquals("09:30", $journee->heureDebut());
        $this->assertEquals("18:00", $journee->heureFin());
    }
    public function test_une_journee_sait_s_afficher()
    {
        $date="1973-06-13";
        $debut="09:30";
        $fin="18:00";
        $journee=new Journee($date,$debut,$fin);
        $this->assertEquals("1973-06-13 09:30 -> 18:00", $journee->__toString());
    }
    public function test_une_journee_a_un_milieu()
    {
        $date="1973-06-13";
        $debut="10:00";
        $fin="19:00";
        $journee=new Journee($date,$debut,$fin);
        $this->assertEquals("14:30", $journee->heureMilieu());
        $debut="10:20";
        $fin="10:30";
        $journee=new Journee($date,$debut,$fin);
        $this->assertEquals("10:25", $journee->heureMilieu());
    }
}
