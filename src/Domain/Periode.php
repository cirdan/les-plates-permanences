<?php

namespace LesPlates\Permanences\Domain;


use Doctrine\Common\Collections\ArrayCollection;
use LesPlates\Permanences\Domain\Exception\EngagementPourJourneeInconnueException;

class Periode
{
    private $nom;
    private $journees;
    private $engagements;

    public function __construct(string $nom)
    {
        $this->nom=$nom;
        $this->journees=new ArrayCollection();
        $this->engagements=new ArrayCollection();
    }
    public function __toString()
    {
        return $this->nom;
    }
    public function ajouterJournee(Journee $journee)
    {
        $this->journees->add($journee);
    }
    public function listeJournees(): ArrayCollection
    {
        return $this->journees;
    }
    public function engagementEstDansJournee(Engagement $engagement,string $date){
        return $engagement->date()===$date;
    }
}