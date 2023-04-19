<?php

namespace App\DataFixtures;

use App\Entity\CharacterCp;
use App\Entity\Note;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class NoteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {


        foreach ($this->getNotes() as $Notes) {
            $note = new Note;
            $cp = $this->getReference($Notes['userId'] . " " . $Notes['cpId'], CharacterCp::class);
            $note->setCharacterCp($cp);
            $note->setTitle($Notes['title']);
            $note->setContent($Notes['content']);
            $manager->persist($note);
        }


        $manager->flush();
    }

    public function getDependencies()
    {
        return [CharacterCpFixtures::class];
    }

    private function getNotes()
    {
        return [];
    }
}
