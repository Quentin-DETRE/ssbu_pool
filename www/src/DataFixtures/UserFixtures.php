<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        foreach($this->getUser() as $key=>$userMail)
        {
            $user = new User();
            $user->setName($userMail["name"]);
            $user->setSurname($userMail["surname"]);
            $user->setRoles($userMail['role']);
            $user->setEmail($key);
            $user->setUsername($userMail["username"]);
            $user->setPassword($this->hasher->hashPassword($user, $userMail["password"]));
            
            $manager->persist($user);

            $this->addReference($key,$user);
        }
        $manager->flush();
    }
    private function getUser()
    {
        return ["q-detre@sfi.fr" => [
            'username' => "QuenDetr",
            'role' => ['ROLE_SUPER_ADMIN'],
            'name' => "Quentin",
            'surname' => "DETRÉ",
            'password' => "Passw0rd",
        ],
        "otakawaisan@gmail.com" => [
            'username' => "Otakawai",
            'role' => [],
            'name' => "Quentin",
            'surname' => "DETRÉ",
            'password' => "Passw0rd",
        ]
    ];
    }
}
