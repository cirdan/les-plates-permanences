<?php
namespace LesPlates\Permanences\Controller;

use LesPlates\Permanences\Domain\Engagement;
use LesPlates\Permanences\Domain\EngagementRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use LesPlates\Permanences\Domain\BenevoleDTO;
use LesPlates\Permanences\Domain\BenevoleRepository;
use LesPlates\Permanences\Domain\Exception\BenevoleContactException;
use LesPlates\Permanences\Domain\Exception\BenevoleException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BenevoleController extends Controller
{
    /**
     * @Route("/benevole/activer-notifications", name="benevole_activer_notification")
     */
    public function activerNotification(Request $request, BenevoleRepository $benevoleRepository)
    {
        $benevole=null;
        if($request->cookies->has('benevole_id')){
            $benevole=$benevoleRepository->findOneById($request->cookies->get("benevole_id"));
        }
        if(!$benevole){
            throw new BenevoleException("Un bénévole est requis");
        }
        try{
            $benevole->activerLesNotificationsParEmail();
        }catch (BenevoleContactException $e){
            return $this->redirectToRoute('benevole_saisie_moyens_contact');
        }

        $benevoleRepository->save($benevole);

        return $this->render('notifications-activees.html.twig');
    }
    /**
     * @Route("/benevole/gerer-notifications", name="benevole_gerer_notifications")
     */
    public function gererNotifications(Request $request,
                                       BenevoleRepository $benevoleRepository,
                                       EngagementRepository $engagementRepository,
                                       SessionInterface $session)
    {
        if($request->cookies->has('benevole_id')){
            $benevole=$benevoleRepository->findOneById($request->cookies->get("benevole_id"));
        }
        if(!$benevole){
            return $this->redirectToRoute('home');
        }
        $benevoleDTO=BenevoleDTO::fromBenevole($benevole);
        $form = $this->createFormBuilder($benevoleDTO)
            ->add('nom', TextType::class)
            ->add('notificationsParEmail', CheckboxType::class,array('required'=>false))
            ->add('email', TextType::class,array('required'=>false))
            ->add('notificationsParTelephone', CheckboxType::class,array('required'=>false))
            ->add('telephoneMobile', TextType::class,array('required'=>false))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer'))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $benevoleDTO = $form->getData();

            $benevole->ajouterEmail($benevoleDTO->email);
            $benevole->ajouterTelephoneMobile($benevoleDTO->telephoneMobile);
            $benevole->enregistrerNom($benevoleDTO->nom);
            if($benevoleDTO->notificationsParEmail && $benevole->email()){
                $benevole->activerLesNotificationsParEmail();
            }else{
                $benevole->desactiverLesNotificationsParEmail();
            }
            if($benevoleDTO->notificationsParTelephone && $benevole->telephoneMobile()){
                $benevole->activerLesNotificationsParTelephone();
            }else{
                $benevole->desactiverLesNotificationsParTelephone();
            }

            $benevoleRepository->save($benevole);
            $this->addFlash(
                'success',
                "Les préférences de notifications ont été enregistrées."
            );


            if($benevole->nom()){

                $engagement=$session->get('engagementEnAttente');
                if($engagement){
                    $engagement->affecterA($benevole);
                    $engagementRepository->save($engagement);
                    $this->addFlash(
                        'success',
                        'Votre engagement a été enregistré !'
                    );
                }
            }

            return $this->redirectToRoute('home');
        }
        return $this->render('saisie-moyens-contacts.html.twig',array(
            'form' => $form->createView(),
        ));
    }

}