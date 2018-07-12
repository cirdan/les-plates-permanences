<?php

namespace LesPlates\Permanences\Domain;


use League\Period\Period;

class Journee
{
    private $periode;

    public function __construct(string $date,string $debut,string $fin)
    {
        $this->periode=new Period(new \DateTimeImmutable($date." ".$debut),new \DateTimeImmutable($date." ".$fin));
    }
    public function date()
    {
        return $this->periode->getStartDate()->format("Y-m-d");
    }
    public function heureDebut()
    {
        return $this->periode->getStartDate()->format("H:i");
    }
    public function heureFin()
    {
        return $this->periode->getEndDate()->format("H:i");
    }
    public function heureMilieu()
    {
        return $this->premierCreneau()->getEndDate()->format("H:i");
    }
    public function __toString()
    {
        return $this->periode->getStartDate()->format("Y-m-d H:i -> ").$this->periode->getEndDate()->format("H:i");
    }
    public function contient(Period $periode){
        return $this->periode->contains($periode);
    }
    public function contientCreneau(Period $periode){

        return ($this->premierCreneau()==$periode || $this->secondCreneau()==$periode);
    }

    private function premierCreneau(){
        $diffEnSecondes=$this->periode->getTimestampInterval();
        $milieuEnSecondes=ceil($diffEnSecondes/2);
        return Period::createFromDuration($this->periode->getStartDate(), $milieuEnSecondes);
    }
    private function secondCreneau(){
        $diffEnSecondes=$this->periode->getTimestampInterval();
        $milieuEnSecondes=ceil($diffEnSecondes/2);
        return Period::createFromDuration($this->premierCreneau()->getEndDate(),$milieuEnSecondes);
    }

}