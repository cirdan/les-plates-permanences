<?php

namespace LesPlates\Permanences\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use LesPlates\Permanences\Domain\Exception\EngagementPourJourneeInconnueException;
use LesPlates\Permanences\Domain\Exception\JourneeInconnueException;

class Agenda
{
    private $engagements;
    private $journees;

    public function __construct(Periode $periode)
    {
        $this->engagements=new ArrayCollection();
        $this->journees=new ArrayCollection();
        foreach($periode->listeJournees() as $journee){
            $this->journees->add(new JourneeAgenda($journee->date(),$journee->heureDebut(),$journee->heureFin()));
        }
    }
    public function inscrire(Engagement $engagement)
    {
        $this->engagements->add($engagement);
        $journee=$this->journee($engagement->date());
        if(!$journee){
            throw new EngagementPourJourneeInconnueException();
        }
        $journee->inscrire($engagement);
    }
    public function listeEngagements()
    {
        return $this->engagements;
    }
    public function listeJournees()
    {
        return $this->journees;
    }
    private function journee(string $date){
        $matching = $this->journees->filter(
            function($journee) use ($date) {
                return $journee->date()==$date;
            }
        );
        if(count($matching)===1){
            return $matching->first();
        }
        if(count($matching)>1){
            throw new JourneeInconnueException("L'agenda contient plusieurs fois la même journée");
        }
        return false;
    }
}