<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function Symfony\Component\Translation\t;

class UsersFixtures extends Fixture
{
    protected $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher=$hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create("fr-FR");
        $genres=["men","women"];
        for ($i=0;$i<20;$i++){
            $sexe=mt_rand(0,1);
            $type=$sexe=0 ?"men":"women";
            $user=new User();
            $user->setPrenom($faker->firstName())
                 ->setNom($faker->lastName())
                ->setEmail($faker->email())
                ->setSexe(intval($sexe))
                ->setAvatar(("https://randomuser.me/api/portraits/".
                    $faker->randomElement($genres).
                    "/".mt_rand(1,99).".jpg"))
                ->setIsVerified(true)
                ->setPassword($this->hasher->hashPassword($user,'password'))
                ;
            $manager->persist($user);
        }
        $admin=new User();
        $admin->setPrenom("admin")
            ->setNom("admin")
            ->setEmail("admin@gmail.com")
            ->setSexe(intval(0))
            ->setRoles(["ROLE_ADMIN"])
            ->setAvatar(("https://randomuser.me/api/portraits/men".
                "/".mt_rand(1,99).".jpg"))
            ->setIsVerified(true)
            ->setPassword($this->hasher->hashPassword($admin,'password'))
        ;
        $manager->persist($admin);

        $manager->flush();
    }
}
