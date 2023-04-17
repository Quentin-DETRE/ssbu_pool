<?php

namespace App\Controller;

use App\Entity\CharacterCp;
use App\Entity\Note;

use Doctrine\DBAL\Driver\Mysqli\Initializer\Charset;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterCpController extends AbstractController
{
    #[Route('/pool', name: 'app_character_cp', methods:'GET')]
    public function index(UserInterface $user, EntityManagerInterface $entityManager): Response
    {
        $fighters = $entityManager->getRepository(CharacterCp::class)->findBy(['user' => $user]);
        return $this->render('character_cp/index.html.twig', [
            'controller_name' => 'CharacterCpController',
            'fighters' => $fighters,
        ]);
    }


    #[Route('/pool', name: 'app_cp_drop', methods:'DELETE')]
    public function dropCp(UserInterface $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        $fighter = $entityManager->getRepository(CharacterCp::class)->findOneBy(['id' => $request->query->get('id')]);
        $notes = $entityManager->getRepository(Note::class)->findBy(['characterCp'=>$fighter]);
        if($this->isCsrfTokenValid('delete' . $fighter->getId(), $request->get('_token'))) 
        {
            if ($notes) {
                foreach($notes as $note){
                    $entityManager->remove($note);
                }
            }
            $entityManager->remove($fighter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_character_cp');
    }

}
