<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterDetailControllerTest extends WebTestCase
{
    use BaseDatabaseTrait;
    public function testNoteCreation(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        //Login with admin user
        $testUser = $userRepository->findOneBy(["email"=>"q-detre@sfi.fr"]);
        $client->loginUser($testUser);
        $this->assertNotNull($testUser, 'The test admin is connected');

        $crawler = $client->request('GET', '/pool' );
        $crawler = $client->clickLink('Hero');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.characterChoiceContainer h1', 'N°72');
        $this->assertSelectorTextNotContains(".noteContainer", "Note test Admin");
        $this->assertSelectorTextNotContains(".noteContainer", "Test the creation of a note for the character.");

        $createNoteBtn = $crawler->selectButton("Add note");

        $formCreateNote = $createNoteBtn->form();

        $crawler = $client->submit($formCreateNote, [
            'note[title]' => "Note test Admin",
            'note[content]' => "Test the creation of a note for the character.",
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(".noteContainer", "Note test Admin");
        $this->assertSelectorTextContains(".noteContainer", "Test the creation of a note for the character.");
    }

    public function testNoteEdit():void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        //Login with admin user
        $testUser = $userRepository->findOneBy(["email"=>"q-detre@sfi.fr"]);
        $client->loginUser($testUser);
        $this->assertNotNull($testUser, 'The test admin is connected');

        $crawler = $client->request('GET', '/pool' );
        $crawler = $client->clickLink('Hero');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.characterChoiceContainer h1', 'N°72');
        $this->assertSelectorTextContains(".noteContainer", "Note test Admin");
        $this->assertSelectorTextContains(".noteContainer", "Test the creation of a note for the character.");

        $this->assertResponseIsSuccessful();
        $crawler = $client->click($crawler->filter('.noteContainer .noteButton > a')->last()->selectLink('Edit')->link());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'SSBU Pool');
        $formUpdate = $crawler->selectButton("Submit")->form();

        $crawler = $client->submit($formUpdate, [
            'note[title]' => "Test Note Admin",
            'note[content]' => "Test the update of a note for the character.",
        ]);

        $this->assertSelectorTextContains('title', 'Redirecting to ');
        $crawler = $client->click($crawler->filter('a')->link());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.characterChoiceContainer h1', 'N°72');
        $this->assertSelectorTextContains(".noteContainer", "Test Note Admin");
        $this->assertSelectorTextContains(".noteContainer", "Test the update of a note for the character.");
    }

    public function testDeleteNote(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        //Login with admin user
        $testUser = $userRepository->findOneBy(["email"=>"q-detre@sfi.fr"]);
        $client->loginUser($testUser);
        $this->assertNotNull($testUser, 'The test admin is connected');

        $crawler = $client->request('GET', '/pool' );
        $crawler = $client->clickLink('Hero');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.characterChoiceContainer h1', 'N°72');
        $this->assertSelectorTextContains(".noteContainer", "Test Note Admin");
        $this->assertSelectorTextContains(".noteContainer", "Test the update of a note for the character.");

        $formDelete = $crawler->filter('.noteContainer .noteButton')->last()->selectButton('Delete')->form();
        $crawler = $client->submit($formDelete);

        $this->assertSelectorTextContains('title', 'Redirecting to ');
        $crawler = $client->click($crawler->filter('a')->link());

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.characterChoiceContainer h1', 'N°72');
        $this->assertSelectorTextNotContains(".noteContainer", "Test Note Admin");
        $this->assertSelectorTextNotContains(".noteContainer", "Test the update of a note for the character.");
    }
}
