<?php

namespace App\Controller;

use App\Entity\Cars;
use App\Entity\Image;
use App\Form\CarType;
use App\Repository\CarsRepository;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;

class CarController extends AbstractController
{
    /**
     * Liste de toutes les voitures sur l'index du répertoire car
     * @param \App\Repository\CarsRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/car', name: 'cars_index')]
    public function index(CarsRepository $repo): Response
    {
        // récupère toutes les données de la table Cars dans la bdd
        $cars = $repo->findAll();
        // renvoie à la vue (liste de toutes les voitures)
        return $this->render('car/index.html.twig', [
            'controller_name' => 'CarController',
            'cars' => $cars,
        ]);
    }

    #[Route('/car/add', name: "car_create")]
    #[IsGranted("ROLE_ADMIN")]
    /**
     * Permet l'ajout d'une voiture via formulaire
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $car = new Cars();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // gestion des images :
            foreach ($car->getImages() as $image) {
                $image->setCar($car);
                $manager->persist($image);
            }
            $manager->persist($car);
            $manager->flush();

            // message flash
            $this->addFlash(
                'success',
                "L'annonce <strong>" . $car->getModel() . "</strong> a bien été enregristrée!"
            );

            // redirection vers l'annonce en elle-même via son slug
            return $this->redirectToRoute('car_show', [
                'slug' => $car->getSlug()
            ]);

        }

        return $this->render('car/add.html.twig', [
            'myForm' => $form->createView()
        ]);
    }


    #[Route("car/{slug}/edit", name: "car_edit")]
    #[IsGranted("ROLE_ADMIN")]
    /**
     * Permet l'édition d'une annonce via formulaire
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @param \App\Entity\Cars $car
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, EntityManagerInterface $manager, Cars $car): Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // gestion des image
            foreach ($car->getImages() as $image) {
                $image->setCar($car);
                $manager->persist($image);
            }

            $manager->persist($car); // pas obligatoire en cas d'update
            $manager->flush();
            $this->addFlash(
                'warning',
                "L'annonce <strong>" . $car->getModel() . "</strong> a bien été modifiée"
            );

            return $this->redirectToRoute('car_show', [
                'slug' => $car->getSlug()
            ]);

        }


        return $this->render("car/edit.html.twig", [
            'myForm' => $form->createView(),
            'car' => $car
        ]);
    }

    #[Route('/car/brand/{brandName}/models', name: 'car_brand_models')]
    /**
     * Liste tous les modèles d'une marque
     * @param string $brandName
     * @param \App\Repository\CarsRepository $repo
     * @param BrandRepository $brandRepo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listBrandModels(string $brandName, CarsRepository $repo, BrandRepository $brandRepo): Response
    {
        // Trouve la marque correspondante
        $brand = $brandRepo->findOneBy(['name' => $brandName]);

        if (!$brand) {
            throw $this->createNotFoundException('Brand not found');
        }

        // Récupère toutes les voitures de la marque
        $cars = $repo->findBy(['brand' => $brand]);

        return $this->render('car/brand_models.html.twig', [
            'brand' => $brand,
            'cars' => $cars,
        ]);
    }

    #[Route('/car/brand/{brandName}', name: 'car_brand_show')]
    /**
     * Permet d'afficher une marque et ses infos
     * @param string $brandName
     * @param \App\Repository\BrandRepository $brandRepo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showBrand(string $brandName, BrandRepository $brandRepo): Response
    {
        // Trouve la marque correspondante
        $brand = $brandRepo->findOneBy(['name' => $brandName]);

        if (!$brand) {
            throw $this->createNotFoundException('Brand not found');
        }

        return $this->render('car/brand_show.html.twig', [
            'brand' => $brand,
        ]);
    }

    /**
     * Permet d'afficher une annonce via son slug en paramètre
     * Attention, dans config/packages/doctrine.yaml, mettre la ligne 26 mettre true à auto_mapping
     * @param CARS $car
     * @return Response
     */
    #[Route('/car/{slug}', name: "car_show")]
    public function show(Cars $car): Response
    {
        return $this->render("car/show.html.twig", [
            'controller_name' => 'CarController',
            'car' => $car,
        ]);
    }
}
