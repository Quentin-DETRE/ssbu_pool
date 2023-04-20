<?php

namespace App\Controller;


use App\Entity\Note;
use App\Form\NoteType;
use App\Repository\CharacterChoiceRepository;
use App\Repository\CharacterCpRepository;
use App\Repository\NoteRepository;
use App\Services\CharacterChoiceManager;
use App\Services\NoteManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class CharacterDetailController extends AbstractController
{
    #[Route('/character/number/{slug}', name: 'app_character_detail', methods: 'GET')]
    public function index(string $slug, UserInterface $user, CharacterCpRepository $characterCpRepository, NoteRepository $noteRepository): Response
    {
        $characterCp =$characterCpRepository->findCharacterDetail($slug, $user);
        if (!$characterCp) {
            return $this->redirectToRoute("app_character_cp");
        }
        return $this->render('character_detail/index.html.twig', [
            'characterCp' =>  $characterCp,
            'notes' => $noteRepository->findByInterationNumber($slug),
            'form' => $this->createForm(NoteType::class, new Note())->createView(),
        ]);
    }

    #[Route('/character/number/{slug}', name: 'app_create_note', methods: 'POST')]
    public function createNote(Request $request,NoteManager $noteManager, string $slug, UserInterface $user): Response
    {

        $noteManager->createNote($slug, $request, $this->createForm(NoteType::class, new Note()), $user);
        return $this->redirectToRoute('app_character_detail', ['slug' => $slug]);
    }

    #[Route('/character/number/{slug}', name: 'app_character_detail_delete', methods: "DELETE")]
    public function delete(EntityManagerInterface $entityManager,NoteManager $noteManager, CharacterChoiceManager $characterDetailManager, string $slug, Request $request, NoteRepository $noteRepository, CharacterChoiceRepository $characterChoiceRepository, CharacterCpRepository $characterCpRepository): Response
    {
        $characterChoice = $characterChoiceRepository->findOneBy(['iterationNumber' => $slug]);
        $characterCps = $characterCpRepository->findBy(['characterChoice' => $characterChoice]);
        if ($this->isCsrfTokenValid('delete' . $characterChoice->getId(), $request->get('_token'))) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
            $characterDetailManager->deleteCharacter($entityManager, $characterCps, $characterChoice, $noteRepository);
        }

        $note = $noteRepository->findOneBy(['id' => $request->query->get('id')]);
        if ($note != null && $this->isCsrfTokenValid('delete' . $note->getId(), $request->get('_token'))) {
            $this->denyAccessUnlessGranted('NOTE_DELETE', $note);
            $noteManager->deleteNote($note);
            return $this->redirectToRoute('app_character_detail', ['slug' => $slug]);
        }

        return $this->redirectToRoute('app_character_cp');
    }
}
