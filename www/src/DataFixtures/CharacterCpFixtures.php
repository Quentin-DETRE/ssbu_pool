<?php

namespace App\DataFixtures;

use App\Entity\CharacterChoice;
use App\Entity\CharacterCp;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CharacterCpFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getCharacterCp() as $championPool) {
            foreach ($championPool['championName'] as $champion) {
                $pool = new CharacterCp();
                $user = $this->getReference($championPool['userEmail']);
                $character = $this->getReference($champion);
                $pool->setUser($user);
                $pool->setCharacterChoice($character);

                $this->addReference($user->getId() . " " . $character->getId(), $pool);

                $manager->persist($pool);
            }
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [UserFixtures::class, CharacterChoiceFixtures::class];
    }
    private function getCharacterCp()
    {
        return [0 => [
            'userEmail' => "q-detre@sfi.fr",
            'championName' => [
                0 => '65',
                1 => '72',
            ],
        ]];
    }
}
