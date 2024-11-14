<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Cars;
use App\Entity\Image;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use GuzzleHttp\Client;
use Symfony\Component\Filesystem\Filesystem;
// use App\Entity\Brand;

class CarsFixtures extends Fixture
{
    private $imagesPath = __DIR__ . '/../../public/images/cars'; // Path to store images

    /**
     * Permet de charger les voitures depuis l'API Pixabay et de les télécharger dans le dossier public/images/cars
     * @param \Doctrine\Persistence\ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $filesystem = new Filesystem();
        $client = new Client();

        $apiKey = '46840045-35d1ab55406a9a1950d534e20';
        $response = $client->request('GET', 'https://pixabay.com/api/', [
            'query' => [
                'key' => $apiKey,
                'q' => 'cars',
                'image_type' => 'photo',
                'per_page' => 60, // Nombre d'images à récupérer
            ],
        ]);

        $data = json_decode($response->getBody(), true); // Convertit la réponse JSON en tableau associatif
        $imageUrls = array_column($data['hits'], 'webformatURL'); // Récupère les URLs des images 

        // Télécharge les images dans le dossier public/images/cars 
        foreach ($imageUrls as $key => $url) {
            $imagePath = $this->imagesPath . "/car_image_{$key}.jpg"; // Chemin complet et création d'un nom unique pour chaque image en utilisant l'index $key
            if (!$filesystem->exists($imagePath)) { /// Vérifie si le fichier existe déjà ou non, si non, télécharge l'image à partir de l'URL et la sauve dans le dossier
                $imageContent = file_get_contents($url);
                file_put_contents($imagePath, $imageContent);
            }
        }

        // charge les images en cache
        $this->loadCarsFromCache($manager);
    }

    /**
     * Permet de charger les données depuis le cache et d'utiliser faker pour générer des données aléatoires
     * @param \Doctrine\Persistence\ObjectManager $manager
     * @return void
     */
    private function loadCarsFromCache(ObjectManager $manager)
    {
        $faker = Factory::create(); // Crée une instance de Faker en appelant la méthode statique create() de la classe Factory
        $slugify = new Slugify();
        $localImageFiles = glob($this->imagesPath . '/*.jpg'); // Récupère la liste des fichiers d'images dans le dossier

        for ($i = 1; $i <= 60; $i++) {
            $car = new Cars();
            $brand = $this->getReference(BrandFixtures::BRANDS_REFERENCE . rand(0, 26));
            $model = $faker->word();
            $slug = strtolower(str_replace(' ', '-', $model));
            $coverImage = '/images/cars/car_image_' . ($i % count($localImageFiles)) . '.jpg';

            $car->setBrand($brand)
                ->setModel($model)
                ->setSlug($slug)
                ->setCoverImage($coverImage) // Use the relative URL for browser access
                ->setYear($faker->numberBetween(2000, 2023))
                ->setPrice($faker->randomFloat(2, 5000, 50000))
                ->setMileage($faker->numberBetween(1000, 200000))
                ->setDescription($faker->paragraph())
                ->setOwner($faker->numberBetween(1, 5))
                ->setEngine($faker->randomFloat(1, 1.0, 5.0))
                ->setHorse($faker->numberBetween(100, 400))
                ->setFuel($faker->randomElement(['Petrol', 'Diesel', 'Electric', 'Hybrid']))
                ->setTransmission($faker->randomElement(['Automatic', 'Manual']))
                ->setOptions($faker->paragraph());

            $manager->persist($car);

            // Ajout d'images aléatoires à la voiture
            for ($j = 1; $j <= rand(2, 5); $j++) {
                $image = new Image();
                $randomImagePath = '/images/cars/car_image_' . rand(0, count($localImageFiles) - 1) . '.jpg';

                $image->setUrl($randomImagePath)
                    ->setCaption($faker->sentence())
                    ->setCar($car);

                $manager->persist($image);
            }
        }

        $manager->flush();
    }
}