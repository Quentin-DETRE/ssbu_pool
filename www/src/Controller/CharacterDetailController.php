<?php

namespace App\Controller;

use App\Entity\CharacterChoice;
use App\Entity\CharacterCp;
use App\Entity\Note;
use App\Form\NoteType;
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
    public function index(EntityManagerInterface $entityManager, string $slug, Request $request, UserInterface $user): Response
    {
        $characterCp = $entityManager->getRepository(CharacterCp::class)->findCharacterDetail($slug, $user);

        $notes = $entityManager->getRepository(Note::class)->findByInterationNumber($slug);

        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);

        return $this->render('character_detail/index.html.twig', [
            'characterCp' => $characterCp,
            'notes' => $notes,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/character/number/{slug}', name: 'app_create_note', methods: 'POST')]
    public function createNote(Request $request, EntityManagerInterface $entityManager, string $slug, UserInterface $user): Response
    {
        $characterChoice = $entityManager->getRepository(CharacterChoice::class)->findOneBy(['iterationNumber' => $slug]);
        $cp = $entityManager->getRepository(CharacterCp::class)->findOneBy(['user' => $user, 'characterChoice' => $characterChoice]);

        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->getData();
            $note->setCharacterCp($cp);
            $entityManager->persist($note);
            $entityManager->flush();
        }


        return $this->redirectToRoute('app_character_detail', ['slug' => $slug]);
    }

    #[Route('/character/number/{slug}', name: 'app_character_detail_delete', methods: "DELETE")]
    public function delete(EntityManagerInterface $entityManager, string $slug, Request $request): Response
    {
        $characterChoice = $entityManager->getRepository(CharacterChoice::class)->findOneBy(['iterationNumber' => $slug]);
        $cps = $entityManager->getRepository(CharacterCp::class)->findBy(['characterChoice' => $characterChoice]);

        $note = $entityManager->getRepository(Note::class)->findOneBy(['id' => $request->query->get('id')]);

        if ($this->isCsrfTokenValid('delete' . $characterChoice->getId(), $request->get('_token'))) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');

            foreach ($cps as $cp) {
                $notes = $entityManager->getRepository(Note::class)->findBy(['characterCp' => $cp]);
                foreach ($notes as $note) {
                    $entityManager->remove($note);
                }
                $entityManager->remove($cp);
            }

            $entityManager->remove($characterChoice);
            $entityManager->flush();
        }

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
