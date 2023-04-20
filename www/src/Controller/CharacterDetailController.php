<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use App\Entity\CharacterCp;
use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\CharacterChoiceRepository;
use App\Repository\CharacterCpRepository;
use App\Repository\NoteRepository;
use App\Services\CharacterChoiceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterDetailController extends AbstractController
{
    #[Route('/character/number/{slug}', name: 'app_character_detail', methods: 'GET')]
    public function index(string $slug, UserInterface $user, CharacterCpRepository $characterCpRepository, NoteRepository $noteRepository): Response
    {
        $characterCp = $characterCpRepository->findCharacterDetail($slug, $user);

        $notes = $noteRepository->findByInterationNumber($slug);

        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);

        if (!$characterCp) {
            return $this->redirectToRoute("app_character_cp");
        }
        return $this->render('character_detail/index.html.twig', [
            'characterCp' => $characterCp,
            'notes' => $notes,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/character/number/{slug}', name: 'app_create_note', methods: 'POST')]
    public function createNote(Request $request, EntityManagerInterface $entityManager, string $slug, UserInterface $user, CharacterChoiceRepository $characterChoiceRepository, CharacterCpRepository $characterCpRepository): Response
    {
        $characterChoice = $characterChoiceRepository->findOneBy(['iterationNumber' => $slug]);
        $characterCp = $characterCpRepository->findOneBy(['user' => $user, 'characterChoice' => $characterChoice]);

        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->getData();
            $note->setCharacterCp($characterCp);
            $entityManager->persist($note);
            $entityManager->flush();
        }


        return $this->redirectToRoute('app_character_detail', ['slug' => $slug]);
    }

    #[Route('/character/number/{slug}', name: 'app_character_detail_delete', methods: "DELETE")]
    public function delete(EntityManagerInterface $entityManager, CharacterChoiceManager $characterDetailManager, string $slug, Request $request, NoteRepository $noteRepository, CharacterChoiceRepository $characterChoiceRepository, CharacterCpRepository $characterCpRepository): Response
    {
        $characterChoice = $characterChoiceRepository->findOneBy(['iterationNumber' => $slug]);
        $characterCps = $characterCpRepository->findBy(['characterChoice' => $characterChoice]);
        if ($this->isCsrfTokenValid('delete' . $characterChoice->getId(), $request->get('_token'))) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
            $characterDetailManager->deleteCharacter($entityManager, $characterCps, $characterChoice, $noteRepository);
        }
        $note = $noteRepository->findOneBy(['id' => $request->query->get('id')]);
        if ($note != null) {
            $this->denyAccessUnlessGranted('NOTE_DELETE', $note);
            if ($this->isCsrfTokenValid('delete' . $note->getId(), $request->get('_token'))) {
                $entityManager->remove($note);
                $entityManager->flush();
                return $this->redirectToRoute('app_character_detail', ['slug' => $slug]);
            }
        }
        return $this->redirectToRoute('app_character_cp');
    }
}
