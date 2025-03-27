<?php

namespace App\Controller;


use App\Entity\Session;
use App\Entity\Formation;
use App\Repository\SessionRepository;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(
        EntityManagerInterface $em,
        FormationRepository $formationRepo,
        SessionRepository $sessionRepo

    ): Response
    
    {
        // 1️ Récupérer l'utilisateur connecté
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        

        // 2️ Données basiques pour la vue
        $firstname = $user->getFirstname();
        $profilePicture = $user->getProfilePicture();
        $userInitials = strtoupper(substr($firstname, 0, 1));

        // 3️ Appels à la BDD (créer des repositories dédiés pour ça)
        $apprenants = $em->getRepository('App\Entity\User')->count(['role' => 'apprenant']);
        $formateurs = $em->getRepository('App\Entity\User')->count(['role' => 'formateur']);
        $sessions = $em->getRepository(Session::class)->count([]);
        $sessionsToday = $sessionRepo->countSessionsToday();


        //  Formations avec le repo dédié
        $formationsToday = $formationRepo->countFormationsToday();
        $totalFormations = $formationRepo->countFormations();


        return $this->render('admin/dashboard.html.twig', [
            'firstname' => $firstname,
            'profilePicture' => $profilePicture,
            'userInitials' => $userInitials,
            'apprenants' => $apprenants,
            'formateurs' => $formateurs,
            'sessions' => $sessions,
            'sessionsToday' => $sessionsToday,
            'formationsToday' => $formationsToday,
            'totalFormations' => $totalFormations,
            'currentDate' => (new \DateTime())->format('d/m/Y')
        ]);
    }

     #[Route('/admin/contacts', name: 'admin_contacts')]
    public function contacts(): Response
    {
    // Temporaire
    return $this->render('admin/contacts.html.twig');
    }

    #[Route('/admin/formations', name: 'admin_formations')]
    public function formations(): Response
    {
    // Temporaire
    return $this->render('admin/formations.html.twig');
    }


#[Route('/admin/create-user', name: 'admin_create_user')]
public function createUser(): Response
{
    // Temporaire : on affiche juste une page blanche ou un formulaire plus tard
    return $this->render('admin/user_create.html.twig');
}

#[Route('/admin/parametres', name: 'admin_parametres')]
public function parametres(): Response
{
    // Temporaire, juste pour tester que le lien fonctionne
    return $this->render('admin/parametres.html.twig');
}


}


