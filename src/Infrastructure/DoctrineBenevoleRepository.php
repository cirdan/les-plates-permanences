<?php

namespace LesPlates\Permanences\Infrastructure;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use LesPlates\Permanences\Domain\Benevole;
use LesPlates\Permanences\Domain\BenevoleRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;



class DoctrineBenevoleRepository extends ServiceEntityRepository implements BenevoleRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Benevole::class);
    }

    public function save(Benevole $benevole): void
    {
        $this->_em->persist($benevole);
        $this->_em->flush();
    }
    public function findOneById($uuid)
    {
        return parent::findOneById($uuid);
    }

}
