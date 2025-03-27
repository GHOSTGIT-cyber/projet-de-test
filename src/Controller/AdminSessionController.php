<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Salle;
use App\Entity\Session;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSessionController extends AbstractController
{
    /**
     * Formulaire de création d'une session
     */
    #[Route('/admin/create-session', name: 'admin_create_session')]
    public function createSessionForm(EntityManagerInterface $em): Response
    {
        $formations = $em->getRepository(Formation::class)->findAll();
        $formateurs = $em->getRepository(User::class)->findBy(['role' => 'formateur']);
        $apprenants = $em->getRepository(User::class)->findBy(['role' => 'apprenant']);
        $groupes = $em->getRepository('App\Entity\Groupe')->findAll();
        $salles = $em->getRepository(Salle::class)->findAll();

        return $this->render('admin/create_session.html.twig', [
            'formations' => $formations,
            'formateurs' => $formateurs,
            'apprenants' => $apprenants,
            'groupes' => $groupes,
            'salles' => $salles,
        ]);
    }

    /**
     * Traitement du formulaire de création d'une session
     */
    #[Route('/admin/create-session/process', name: 'admin_create_session_process', methods: ['POST'])]
    public function createSessionProcess(Request $request, EntityManagerInterface $em): Response
    {
        $nomSession = $request->request->get('nom_session');
        $formationId = $request->request->get('formation_id');
        $formateurId = $request->request->get('formateur_id');
        $dateDebut = new \DateTime($request->request->get('date_debut'));
        $dateFin = new \DateTime($request->request->get('date_fin'));
        $salleNom = $request->request->get('salle_nom');
        $choixApprenants = $request->request->get('choix_apprenants');
        $groupeIds = $request->request->all('groupe_id');
        $apprenantIds = $request->request->all('apprenants');
    
        $em->getConnection()->beginTransaction();
    
        try {
            // 🔧 Sécurité sur formation & formateur
            $formation = $em->getRepository(Formation::class)->find($formationId);
            $formateur = $em->getRepository(User::class)->find($formateurId);
    
            if (!$formation || !$formateur) {
                throw new \Exception('Formation ou formateur introuvable.');
            }
    
            // Gestion de la salle
            $salle = $em->getRepository(Salle::class)->findOneBy(['nom' => $salleNom]);
            if (!$salle) {
                $salle = new Salle();
                $salle->setNom($salleNom);
                $em->persist($salle);
                $em->flush();
            }
    
            // Création de la session
            $session = new Session();
            $session->setNom($nomSession);
            $session->setFormation($formation);
            $session->setFormateur($formateur);
            $session->setSalle($salle);
            $session->setDateDebut($dateDebut);
            $session->setDateFin($dateFin);
    
            $em->persist($session);
            $em->flush();
    
            // 🟣 Gestion du choix groupes ou apprenants
            if ($choixApprenants === 'groupe' && !empty($groupeIds)) {
                foreach ($groupeIds as $groupeId) {
                    $groupe = $em->getRepository('App\Entity\Groupe')->find($groupeId);
                    if ($groupe) {
                        // Ajouter les participants du groupe à la session
                        // Exemple : $session->addGroupe($groupe);
                    }
                }
            }
    
            if ($choixApprenants === 'individuel' && !empty($apprenantIds)) {
                foreach ($apprenantIds as $apprenantId) {
                    $apprenant = $em->getRepository(User::class)->find($apprenantId);
                    if ($apprenant) {
                        // Ajouter l'apprenant à la session
                        // Exemple : $session->addApprenant($apprenant);
                    }
                }
            }
    
            $em->getConnection()->commit();
    
            $this->addFlash('success', 'Session créée avec succès !');
            return $this->redirectToRoute('admin_dashboard');
    
        } catch (\Exception $e) {
            $em->getConnection()->rollBack();
            $this->addFlash('error', 'Erreur lors de la création de la session : ' . $e->getMessage());
            return $this->redirectToRoute('admin_create_session');
        }
    }
}