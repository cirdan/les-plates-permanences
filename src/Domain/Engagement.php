<?php

namespace LesPlates\Permanences\Domain;


use League\Period\Period;
use LesPlates\Permanences\Domain\Exception\EngagementEstPasUnCreneauException;
use LesPlates\Permanences\Domain\Exception\EngagementNonComprisDansJourneeException;

class Engagement
{
    public $id;
    public $debut;
    public $fin;

    private $benevole;

    private $periode;

    public function __construct(Journee $journee,string $debut,string $fin, Benevole $benevole)
    {

        $this->benevole=$benevole;
        $this->periode=new Period(new \DateTimeImmutable($journee->date()." ".$debut),new \DateTimeImmutable($journee->date()." ".$fin));
        if(!$journee->contient($this->periode)){
            throw new EngagementNonComprisDansJourneeException("Ce créneau est en dehors des horaires définis");
        }
        if(!$journee->contientCreneau($this->periode)){
            throw new EngagementEstPasUnCreneauException("Ce créneau n'est pas valide");
        }
        $this->debut=$this->periode->getStartDate();
        $this->fin=$this->periode->getEndDate();
    }
    public function date()
    {
        return $this->periode->getStartDate()->format("Y-m-d");
    }
    public function benevole()
    {
        return $this->benevole;
    }
    public function affecterA(Benevole $benevole)
    {
        $this->benevole=$benevole;
    }
    public function heureDebut()
    {
        return $this->periode->getStartDate()->format("H:i");
    }
    public function heureFin()
    {
        return $this->periode->getEndDate()->format("H:i");
    }
    public function __toString()
    {
        return $this->periode->getStartDate()->format("Y-m-d H:i -> ").$this->periode->getEndDate()->format("H:i");
    }

    public function __wakeup()
    {
        if ($this->id) {
            $this->periode=new Period($this->debut,$this->fin);
        }
    }
}