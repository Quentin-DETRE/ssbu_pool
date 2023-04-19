<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CharacterType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class UpdateCharacterController extends AbstractController
{
    #[Route('/character/update/{slug}', name: 'app_update_character')]
    public function index(EntityManagerInterface $entityManager, Request $request, string $slug, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $characterChoice = $entityManager->getRepository(CharacterChoice::class)->findOneBy(['iterationNumber' => $slug]);
        $form = $this->createForm(CharacterType::class, $characterChoice);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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


            $entityManager->persist($characterChoice);
            $entityManager->flush();


            return $this->redirectToRoute('app_character_detail', ['slug' => $characterChoice->getIterationNumber()]);
        }
        return $this->render('update_character/index.html.twig', [
            'form' => $form->createView(),
            'characterChoice' => $characterChoice,
        ]);
    }
}
