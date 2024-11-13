<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BrandFixtures extends Fixture
{
    public const BRANDS_REFERENCE = 'brands';

    /**
     * Permet de charger les marques de voitures dans la base de données
     * @param \Doctrine\Persistence\ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $brandsData = [
            ['Audi', 'Germany', 1909],
            ['BMW', 'Germany', 1916],
            ['Buick', 'USA', 1899],
            ['Cadillac', 'USA', 1902],
            ['Chevrolet', 'USA', 1911],
            ['Chrysler', 'USA', 1925],
            ['Citroen', 'France', 1919],
            ['Dodge', 'USA', 1900],
            ['Ford', 'USA', 1903],
            ['GMC', 'USA', 1911],
            ['Honda', 'Japan', 1948],
            ['Hyundai', 'South Korea', 1967],
            ['Jeep', 'USA', 1941],
            ['Kia', 'South Korea', 1944],
            ['Lexus', 'Japan', 1989],
            ['Mercedes Benz', 'Germany', 1926],
            ['Mini', 'UK', 1959],
            ['Mitsubishi', 'Japan', 1917],
            ['Nissan', 'Japan', 1933],
            ['Peugeot', 'France', 1810],
            ['Porsche', 'Germany', 1931],
            ['Renault', 'France', 1899],
            ['Seat', 'Spain', 1950],
            ['Subaru', 'Japan', 1953],
            ['Tesla', 'USA', 2003],
            ['Toyota', 'Japan', 1937],
            ['Volkswagen', 'Germany', 1937]
        ];

        foreach ($brandsData as $key => $data) {
            [$name, $country, $founded] = $data;
            $brand = new Brand();
            $brand->setName($name)
                  ->setCountry($country)
                  ->setFounded($founded);

            $manager->persist($brand);

            // stocke la marque dans une référence pour pouvoir l'utiliser dans d'autres fixtures
            $this->addReference(self::BRANDS_REFERENCE . $key, $brand);
        }

        $manager->flush();
    }
}