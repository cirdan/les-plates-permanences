<?php

namespace LesPlates\Permanences\Domain;


use Doctrine\Common\Collections\ArrayCollection;
use LesPlates\Permanences\Domain\Exception\EngagementEstUnDoublonException;
use LesPlates\Permanences\Domain\Exception\JourneeException;

class JourneeAgenda extends Journee
{
    const CRENEAU_1=1;
    const CRENEAU_2=2;

    public $engagements;

    public function __construct(string $date,string $debut,string $fin)
    {
        parent::__construct($date, $debut, $fin);
        $this->engagements=new ArrayCollection();
    }
    public function inscrire(Engagement $engagement)
    {
        if($this->engagementDejaInscrit($engagement)){
            throw new EngagementEstUnDoublonException();
        }
        $this->engagements->add($engagement);
    }
    public function engagementCreneau1()
    {
        return $this->engagementCreneau(self::CRENEAU_1);
    }
    public function engagementCreneau2()
    {
        return $this->engagementCreneau(self::CRENEAU_2);
    }
    private function engagementCreneau($creneau)
    {
        if($creneau===self::CRENEAU_1){
            $matching = $this->filtrerEngagement($this->heureDebut(),$this->heureMilieu());
        }
        if($creneau===self::CRENEAU_2){
            $matching = $this->filtrerEngagement($this->heureMilieu(),$this->heureFin());
        }
        if(!count($matching)){
            return null;
        }
        if(count($matching)===1){
            return $matching->first();
        }
        throw new JourneeException("Plusieurs engagements pour ce créneau.");
    }
    private function engagementDejaInscrit(Engagement $engagement)
    {
        $matching = $this->filtrerEngagement($engagement->heureDebut(),$engagement->heureFin(),$engagement->benevole());
        if(count($matching)>=1){
            return true;
        }
        return false;
    }
    private function filtrerEngagement($heureDebut, $heureFin, Benevole $benevole=null)
    {
        $matching = $this->engagements->filter(
            function($engagement) use ($heureDebut,$heureFin,$benevole) {
                if($benevole){
                    return ($engagement->heureDebut()===$heureDebut && $engagement->heureFin()===$heureFin && $engagement->benevole()===$benevole);
                }
                return ($engagement->heureDebut()===$heureDebut && $engagement->heureFin()===$heureFin);
            }
        );
        return $matching;
    }
}