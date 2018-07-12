<?php
namespace LesPlates\Permanences\Controller;

use LesPlates\Permanences\Domain\Agenda;
use LesPlates\Permanences\Domain\Benevole;
use LesPlates\Permanences\Domain\BenevoleRepository;
use LesPlates\Permanences\Domain\EngagementRepository;
use LesPlates\Permanences\Domain\Exception\EngagementPourJourneeInconnueException;
use LesPlates\Permanences\Domain\JourneeRepository;
use LesPlates\Permanences\Domain\Periode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AgendaController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, JourneeRepository $journeeRepository,EngagementRepository $engagementRepository,BenevoleRepository $benevoleRepository)
    {
        $benevole=null;
        if($request->cookies->has('benevole_id')){
            $benevole=$benevoleRepository->findOneById($request->cookies->get("benevole_id"));
        }
        if(!$benevole){
            $benevole=new Benevole();
            $benevoleRepository->save($benevole);
        }
        $periode=new Periode('Été 2018');
        foreach($journeeRepository->findAll() as $journee){
            $periode->ajouterJournee($journee);
        }
        $agenda=new Agenda($periode);
        foreach($engagementRepository->findByBenevole($benevole) as $engagement){
            try{
                $agenda->inscrire($engagement);
            }catch(EngagementPourJourneeInconnueException $e){
                // TODO: we should grab only the relevant engagements
            }
        }
        $response = $this->render('agenda.html.twig',
            array(
                'agenda' => $agenda,
                'benevole' => $benevole,
            )
        );
        $response->headers->setCookie(new Cookie('benevole_id', $benevole->id(), 0, '/'));
        return $response;
    }
}