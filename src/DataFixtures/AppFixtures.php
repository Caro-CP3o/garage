<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
// use Faker\Factory;
// use Cocur\Slugify\Slugify;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    /**
     * Summary of __construct
     * @param \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        // compte admin 
        $admin = new User();
        $admin->setFirstName("Carole")
            ->setLastName("Pes")
            ->setEmail("carolepes@gmail.com")
            ->setPassword($this->passwordHasher->hashPassword($admin, 'password'))
            ->setIntroduction("Lorem ipsum dolor sit amet,")
            ->setDescription("<p>Praesent mauris purus, luctus nec sem a, scelerisque aliquam mi. Morbi a libero eget ante sodales rutrum ut vitae dolor. Phasellus aliquet finibus sem. Curabitur tristique porta nisl vel sodales. Aliquam erat volutpat. Vestibulum consequat euismod nisl, at pulvinar lectus viverra non. Suspendisse a blandit justo, at porta tellus. Praesent dapibus felis risus, ut ornare enim ultricies eget.</p><p>Morbi finibus vel velit ut consequat. Etiam iaculis hendrerit nunc, ac dapibus mi iaculis ac. Praesent eget enim eget augue hendrerit mollis a tempus diam. Cras vulputate enim at ipsum interdum fringilla. Suspendisse dictum tristique lorem, nec commodo nisl commodo dignissim.</p>")
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();
    }
}
