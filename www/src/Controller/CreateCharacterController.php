<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use App\Form\CharacterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class CreateCharacterController extends AbstractController
{
    #[Route('/character/create', name: 'app_create_character')]
    public function index(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
        $character = new CharacterChoice();
        $form = $this->createForm(CharacterType::class, $character);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {
            $character = $form->getData();
            $image = $form->get('imagePath')->getData();

            if($image) {
                $originalImageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

                $safeImageName = $slugger->slug($originalImageName);
                $newImageName = $safeImageName.'_SSBU.'.$image->guessExtension();
            
                try {
                    $image->move(
                        $this->getParameter('fighters_directory'), $newImageName
                    );
                } catch (FileException $e){
                    dd("CPT");
                }

                $character->setImagePath($newImageName);
            }

            $entityManager->persist($character);
            $entityManager->flush();

            $imagine = new Imagine();
            $fullFile = "fighters/". $newImageName;
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
                

            return $this->redirectToRoute('app_character_choice');
        }

        return $this->render('create_character/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
