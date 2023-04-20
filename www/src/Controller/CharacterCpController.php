<?php

namespace App\Controller;

use App\Entity\CharacterCp;
use App\Entity\Note;

use App\Entity\User;
use App\Repository\CharacterCpRepository;
use App\Repository\NoteRepository;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Charset;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterCpController extends AbstractController
{
    #[Route('/pool', name: 'app_character_cp', methods: 'GET')]
    public function index(UserInterface $user, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $characterCps = $entityManager->getRepository(CharacterCp::class)->findAllCpByUser($user);
        return $this->render('character_cp/index.html.twig', [
            'characterCps' => $characterCps,
        ]);
    }


    #[Route('/pool', name: 'app_cp_drop', methods: 'DELETE')]
    public function dropCp(EntityManagerInterface $entityManager, Request $request, CharacterCpRepository $characterCpRepository, NoteRepository $noteRepository): Response
    {
        $characterCp = $characterCpRepository->findOneBy(['id' => $request->query->get('id')]);
        $notes = $noteRepository->findBy(['characterCp' => $characterCp]);
        if ($this->isCsrfTokenValid('delete' . $characterCp->getId(), $request->get('_token'))) {
            if ($notes) {
                foreach ($notes as $note) {
                    $entityManager->remove($note);
                }
            }
            $entityManager->remove($characterCp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_character_cp');
    }
}
