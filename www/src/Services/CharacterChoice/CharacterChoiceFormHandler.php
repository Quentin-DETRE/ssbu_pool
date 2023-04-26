<?php

namespace App\Services\CharacterChoice;

use App\Entity\CharacterChoice;
use App\Model\Search\CharacterChoiceSearch;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class CharacterChoiceFormHandler
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function handleSearchForm(FormInterface $form, Request $request):CharacterChoiceSearch
    {
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            return $form->getData();
        }
        return new CharacterChoiceSearch();
    }

    public function handleCreateForm(FormInterface $form, Request $request): ?CharacterChoice
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $characterChoice = $form->getData();
            $image = $form->get('imagePath')->getData();

            if ($image) {
                $originalImageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                $safeImageName = $this->slugger->slug($originalImageName);
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

            return $characterChoice;
        }
        return null;
    }

    public function handleUpdateForm(FormInterface $form, Request $request ):CharacterChoice
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $characterChoice = $form->getData();
            $image = $form->get('imagePath')->getData();

            if ($image) {
                $originalImageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                $safeImageName = $this->slugger->slug($originalImageName);
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

            return $characterChoice;
        }
        return new CharacterChoice();
    }
}