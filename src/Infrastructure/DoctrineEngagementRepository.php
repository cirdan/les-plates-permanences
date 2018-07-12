<?php

namespace LesPlates\Permanences\Infrastructure;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use LesPlates\Permanences\Domain\Benevole;
use LesPlates\Permanences\Domain\Engagement;
use LesPlates\Permanences\Domain\EngagementRepository;
use LesPlates\Permanences\Domain\Exception\EngagementEstUnDoublonException;
use Symfony\Bridge\Doctrine\RegistryInterface;



class DoctrineEngagementRepository extends ServiceEntityRepository implements EngagementRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Engagement::class);
    }

    public function save(Engagement $engagement): void
    {
        if($this->engagementExiste($engagement)){
            throw new EngagementEstUnDoublonException();
        }

        $this->_em->persist($engagement);
        $this->_em->flush();
    }
    public function findAll(){
        return parent::findAll();
    }

    public function findByBenevole(Benevole $benevole){
        return parent::findByBenevole($benevole);
    }

    public function findById($uuid)
    {
        return parent::findOneById($uuid);
    }

    public function remove(Engagement $engagement)
    {
        $this->_em->remove($engagement);
        $this->_em->flush();
    }
    public function engagementExiste(Engagement $engagement): bool
    {
        if($this->_em->contains($engagement->benevole())){ // sinon, il n'y a pas de risque d'avoir un doublon
            $engagementExistant=parent::findOneBy(
                array(
                    "debut" => new \DateTime($engagement->date()." ".$engagement->heureDebut()),
                    "fin" => new \DateTime($engagement->date()." ".$engagement->heureFin()),
                    "benevole" => $engagement->benevole(),
                ));
            if($engagementExistant){
                return true;
            }
        }
        return false;
    }


}
