<?php
namespace LesPlates\Permanences\Controller;

use LesPlates\Permanences\Domain\Benevole;
use LesPlates\Permanences\Domain\BenevoleRepository;
use LesPlates\Permanences\Domain\Exception\BenevoleContactException;
use LesPlates\Permanences\Domain\Exception\EngagementEstUnDoublonException;
use LesPlates\Permanences\Domain\Exception\EngagementException;
use LesPlates\Permanences\Domain\JourneeRepository;
use LesPlates\Permanences\Domain\Engagement;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use LesPlates\Permanences\Domain\EngagementRepository;

class EngagementController extends Controller
{
    /**
     * @Route("/engagement/creer/{date}/{heureDebut}-{heureFin}", name="creer_engagement")
     */
    public function creerEngagement(Request $request,
                                    JourneeRepository $journeeRepo,
                                    EngagementRepository $engagementRepo,
                                    BenevoleRepository $benevoleRepository,
                                    SessionInterface $session,
                                    $date,
                                    $heureDebut,
                                    $heureFin)
    {
        $benevole=null;
        $session->remove('engagementEnAttente');
        if($request->cookies->has('benevole_id')){
            $benevole=$benevoleRepository->findOneById($request->cookies->get("benevole_id"));
        }
        if(!$benevole){
            $benevole=new Benevole();
        }
        $journee=$journeeRepo->findOneByDate($date);
        $engagement = new Engagement($journee, $heureDebut, $heureFin, $benevole);
        if($engagementRepo->engagementExiste($engagement)){
            return $this->redirectToRoute('home');
        }
        if(!$benevole->nom()){
            $session->set('engagementEnAttente', $engagement);
            return $this->redirectToRoute('benevole_gerer_notifications');
        }
        $engagementRepo->save($engagement);


        return $this->redirectToRoute('home');
    }
    /**
     * @Route("/engagement/annuler/{uuid}", name="annuler_engagement")
     */
    public function annulerEngagement(Request $request, BenevoleRepository $benevoleRepository, EngagementRepository $engagementRepo, $uuid)
    {
        $benevole=null;
        if($request->cookies->has('benevole_id')){
            $benevole=$benevoleRepository->findOneById($request->cookies->get("benevole_id"));
        }
        if(!$benevole){
            $benevole=new Benevole();
        }
        $engagement = $engagementRepo->findById($uuid);
        if(!$engagement){
            return $this->redirectToRoute('home');
        }

        $engagementRepo->remove($engagement);

        return $this->redirectToRoute('home');
    }
}