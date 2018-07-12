<?php

namespace LesPlates\Permanences\Tests\Domain;

use LesPlates\Permanences\Domain\Benevole;
use LesPlates\Permanences\Domain\Exception\EngagementEstUnDoublonException;
use LesPlates\Permanences\Domain\Journee;
use LesPlates\Permanences\Domain\Engagement;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as TestCase;

class DoctrineEngagementRepositoryTest extends TestCase
{

    protected $repository;

    public function test_on_ne_peut_pas_ajouter_deux_engagements_pour_un_meme_creneau_pour_le_meme_benevole()
    {
        $this->expectException(EngagementEstUnDoublonException::class);
        $benevole = new Benevole();
        $journee = new Journee("1973-03-13", "10:00", "16:00");
        $debut = "10:00";
        $fin = "13:00";
        $engagement = new Engagement($journee, $debut, $fin,$benevole);
        $this->repository->save($engagement);
        $engagement2 = new Engagement($journee, $debut, $fin,$benevole);
        $this->repository->save($engagement2);
        $this->repository->remove($engagement);
    }

    public function test_on_peut_avoir_deux_benevoles_differents_pour_un_meme_creneau()
    {
        $benevole = new Benevole();
        $benevole2 = new Benevole();
        $journee = new Journee("1973-03-13", "10:00", "16:00");
        $debut = "10:00";
        $fin = "13:00";
        $engagement = new Engagement($journee, $debut, $fin,$benevole);
        $this->repository->save($engagement);
        $engagement2 = new Engagement($journee, $debut, $fin,$benevole2);
        $this->repository->save($engagement2);
        $engagements=$this->repository->findBy([
            "debut" => new \DateTime($engagement->date()." ".$engagement->heureDebut()),
            "fin" => new \DateTime($engagement->date()." ".$engagement->heureFin()),
        ]);
        $this->assertCount(2,$engagements);
    }


    public function test_on_peut_ajouter_et_supprimer_un_engagement()
    {
        $journee = $this->createMock(Journee::class);
        $journee->method("contient")->willReturn(true);
        $journee->method("contientCreneau")->willReturn(true);
        $benevole = new Benevole();
        $engagement = new Engagement($journee,'10:00','14:00',$benevole);
        $nbEngagementsAvant = count($this->repository->findAll());
        $this->repository->save($engagement);
        $this->assertEquals($nbEngagementsAvant + 1, count($this->repository->findAll()));
        $this->repository->remove($engagement);
        $this->assertEquals($nbEngagementsAvant, count($this->repository->findAll()));
    }

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->repository = $this->entityManager->getRepository(Engagement::class);
        foreach($this->repository->findAll() as $engagement){
            $this->entityManager->remove($engagement);
        }
        $this->entityManager->flush();
    }

    protected function tearDown()
    {

        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}