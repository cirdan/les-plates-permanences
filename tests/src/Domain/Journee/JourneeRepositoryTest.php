<?php

namespace LesPlates\Permanences\Tests\Domain;

use LesPlates\Permanences\Domain\Journee;
use LesPlates\Permanences\Domain\JourneeAgenda;
use LesPlates\Permanences\Domain\JourneeRepository;
use LesPlates\Permanences\Domain\Exception\JourneeInconnueException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\DateTime;

class JourneeRepositoryTest extends TestCase
{
    public function test_le_repository_connait_les_journees()
    {
        $repo = new JourneeRepository();
        $this->assertGreaterThan(0, count($repo->findAll()));
    }
    public function test_une_journee_inconnue_envoie_une_exception()
    {
        $this->expectException(JourneeInconnueException::class);
        $repo = new JourneeRepository();
        $repo->findOneByDate("2020-01-04");
    }
    public function test_on_sait_trouver_une_journee()
    {
        $journee=new JourneeAgenda("2018-07-09","11:00","18:00");
        $repo = new JourneeRepository();
        $this->assertEquals($journee,$repo->findOneByDate("2018-07-09"));
    }
}