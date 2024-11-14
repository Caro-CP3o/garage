<?php

namespace App\Controller;

use App\Repository\CarsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Permet de récupérer toutes les voitures
     * @param \App\Repository\CarsRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     * La même chose avec injection de dépendance
     */
    #[Route('/', name: 'home')]
    // public function index(EntityManagerInterface $entityManager): Response
    // {
    //     $cars = $entityManager->getRepository(Cars::class)->findAll();

    //     return $this->render('/home.html.twig', [
    //         'cars' => $cars,
    //     ]);
    // } on utilise le Repository à la place de l'entityManager = injection de dépendance
    public function index(CarsRepository $repo): Response
    {
        // récupère toutes les données de la table Cars dans la bdd
        $cars = $repo->findAll();
        // renvoie à la vue
        return $this->render('/home.html.twig', [
            'controller_name' => 'HomeController',
            'cars' => $cars,
        ]);
    }


}
