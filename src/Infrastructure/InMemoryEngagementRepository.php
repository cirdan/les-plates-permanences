<?php

namespace LesPlates\Permanences\Infrastructure;

use Doctrine\Common\Collections\ArrayCollection;
use LesPlates\Permanences\Domain\Benevole;
use LesPlates\Permanences\Domain\Engagement;
use LesPlates\Permanences\Domain\EngagementRepository;

class InMemoryEngagementRepository  implements EngagementRepository
{
    private $engagements;
    public function __construct()
    {
        $this->engagements=new ArrayCollection();
    }
    public function findAll()
    {
        return $this->engagements;
    }

    public function findByBenevole(Benevole $benevole)
    {
        $selection=new ArrayCollection();
        foreach($this->findAll() as $engagement){
            if($engagement->benevole()->id()==$benevole->id()){
                $selection->add($engagement);
            }
        }
        return $selection;
    }
    public function save(Engagement $engagement): void
    {
        $this->engagements->add($engagement);
    }

    public function findById($uuid)
    {
        // TODO: Implement findById() method.
    }

    public function remove(Engagement $engagement)
    {
        $this->engagements->removeElement($engagement);
    }
    public function engagementExiste(Engagement $engagement): bool
    {
        foreach($this->engagements as $engagementExistant){
            if($engagement->estEgal($engagementExistant)){
                return true;
            }
        }
        return false;
    }

}
