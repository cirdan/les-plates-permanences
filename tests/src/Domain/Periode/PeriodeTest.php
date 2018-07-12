<?php

namespace LesPlates\Permanences\Tests\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use LesPlates\Permanences\Domain\Engagement;
use LesPlates\Permanences\Domain\Exception\EngagementPourJourneeInconnueException;
use LesPlates\Permanences\Domain\Journee;
use LesPlates\Permanences\Domain\Periode;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\DateTime;

class PeriodeTest extends TestCase
{
    public function test_une_periode_a_un_nom()
    {
        $periode=new Periode("Été 2018");
        $this->assertEquals("Été 2018", $periode->__toString());

    }
    public function test_on_peut_ajouter_une_journee_a_une_periode()
    {
        $periode=new Periode("Été 2018");
        $journee=$this->createMock(Journee::class);
        $periode->ajouterJournee($journee);
        $journees=new ArrayCollection([$journee]);
        $this->assertEquals($journees, $periode->listeJournees());

    }

}
