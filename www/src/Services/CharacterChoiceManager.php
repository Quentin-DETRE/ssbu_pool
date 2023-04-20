<?php

namespace App\Services;

use App\Entity\CharacterChoice;
use App\Entity\CharacterCp;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class CharacterChoiceManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function  createCharacter(FormInterface $form, SluggerInterface $slugger, Request $request):CharacterChoice
    {

        $characterChoice = $form->getData();
        $image = $form->get('imagePath')->getData();

        if ($image) {
            $originalImageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            $safeImageName = $slugger->slug($originalImageName);
            $newImageName = $safeImageName . '_SSBU.' . $image->guessExtension();

            try {
                $image->move(
                    "/var/www/public/fighters",
                    $newImageName
                );
            } catch (FileException $e) {
                dd("CPT");
            }

            $characterChoice->setImagePath($newImageName);

            $imagine = new Imagine();
            $fullFile = "fighters/" . $newImageName;
            $reduceFile = "fighters/250_" . $newImageName;
            list($iwidth, $iheight) = getimagesize($fullFile);
            $ratio = $iwidth / $iheight;
            $width = 200;
            $height = 150;
            if ($width / $height > $ratio) {
                $width = $height * $ratio;
            } else {
                $height = $width / $ratio;

            }
            $photo = $imagine->open($fullFile);
            $photo->resize(new Box($width, $height))->save($reduceFile);
        } else {
            $characterChoice->setImagePath('Mario_SSBU.png');
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($characterChoice);
            $this->entityManager->flush();
        }
        return $characterChoice;
    }
    public function updateCharacter(formInterface $form, SluggerInterface $slugger, Request $request): CharacterChoice
    {
        $characterChoice = $form->getData();
        $image = $form->get('imagePath')->getData();

        if ($image) {
            $originalImageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            $safeImageName = $slugger->slug($originalImageName);
            $newImageName = $safeImageName . '_SSBU.' . $image->guessExtension();

            try {
                $image->move(
                    $this->getParameter('fighters_directory'),
                    $newImageName
                );
            } catch (FileException $e) {
                dd("CPT");
            }

            $characterChoice->setImagePath($newImageName);

            $imagine = new Imagine();
            $fullFile = "fighters/" . $newImageName;
            $reduceFile = "fighters/250_" . $newImageName;
            list($iwidth, $iheight) = getimagesize($fullFile);
            $ratio = $iwidth / $iheight;
            $width = 200;
            $height = 150;
            if ($width / $height > $ratio) {
                $width = $height * $ratio;
            } else {
                $height = $width / $ratio;
            }
            $photo = $imagine->open($fullFile);
            $photo->resize(new Box($width, $height))->save($reduceFile);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($characterChoice);
            $this->entityManager->flush();
        }
        return $characterChoice;
    }
    public function deleteCharacter( EntityManagerInterface $entityManager, array $characterCps, CharacterChoice $characterChoice, NoteRepository $noteRepository ):void
    {
        $filesystem = new Filesystem();
        foreach ($characterCps as $characterCp) {
            $notes = $noteRepository->findBy(['characterCp' => $characterCp]);
            foreach ($notes as $note) {
                $entityManager->remove($note);
            }

            $entityManager->remove($characterCp);
        }

        if($characterChoice->getImagePath() != 'Mario_SSBU.png') {
            $filesystem->remove('fighters/'.$characterChoice->getImagePath());
            $filesystem->remove('fighters/250_'.$characterChoice->getImagePath());
        }
        $entityManager->remove($characterChoice);
        $entityManager->flush();
    }
}