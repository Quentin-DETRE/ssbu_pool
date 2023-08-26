<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class CharacterChoiceControllerTest extends WebTestCase
{
    use BaseDatabaseTrait;
    public function testCharacterChoiceCreate(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        //Login with admin user
        $testUser = $userRepository->findOneBy(["email"=>"q-detre@sfi.fr"]);
        $client->loginUser($testUser);
        $this->assertNotNull($testUser, 'The test admin is connected');
        $crawler = $client->request('GET', '/character');

        //Test connection with logged admin user
        $this->assertResponseIsSuccessful();
        $this->assertFalse($crawler->selectButton("Character Name") === $crawler->filter('#gridCharacterChoice form button')->last());

        $crawler = $client->click($crawler->filter('.chose-character a')->link());
        //Create the character
        $this->assertResponseIsSuccessful();
        $formCreate = $crawler->selectButton("Submit")->form();
        $formCreate["character_choice[tier]"]->select("A+");
        $formCreate["character_choice[serie]"]->select("20");
        $crawler = $client->submit($formCreate, [
            "character_choice[name]" => "Character Name",
            "character_choice[weight]" => "Heavy",
            "character_choice[speed]" => "Slow",
            "character_choice[iterationNumber]" => "84",
        ]);

        //Assert that the character has been created
        $this->assertSelectorTextContains('title', 'Redirecting to ');
        $crawler = $client->click($crawler->filter('a')->link());
        $this->assertSelectorTextContains("#gridCharacterChoice", "Character Name");

        //Delete the new character
        $formAddCharacterChoice = $crawler->selectButton("Character Name")->form();
        $crawler = $client->submit($formAddCharacterChoice);
        $this->assertSelectorTextContains('title', 'Redirecting to /pool');
        $crawler = $client->clickLink('/pool');
        $crawler = $client->clickLink("Character Name");
        $deleteCharacterForm = $crawler->selectButton("Delete")->form();
        $crawler = $client->submit($deleteCharacterForm);
    }
    public function testCharacterChoiceEdit():void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        //Login with admin user
        $testUser = $userRepository->findOneBy(["email"=>"q-detre@sfi.fr"]);
        $client->loginUser($testUser);
        $this->assertNotNull($testUser, 'The test admin is connected');
        $crawler = $client->request('GET', '/character');

        //Create Test Character for Edit & delete
        $crawler = $client->click($crawler->filter('.chose-character a')->link());
        $formCreate = $crawler->selectButton("Submit")->form();
        $formCreate["character_choice[tier]"]->select("A+");
        $formCreate["character_choice[serie]"]->select("20");
        $crawler = $client->submit($formCreate, [
            "character_choice[name]" => "Character Fixture",
            "character_choice[weight]" => "Heavy",
            "character_choice[speed]" => "Slow",
            "character_choice[iterationNumber]" => "83",
        ]);
        $this->assertSelectorTextContains('title', 'Redirecting to ');
        $crawler = $client->click($crawler->filter('a')->link());

        //Add character to the pool
        $formAddCharacterChoice = $crawler->selectButton("Character Fixture")->form();
        $crawler = $client->submit($formAddCharacterChoice);
        //Navigate to the pool
        $this->assertSelectorTextContains('title', 'Redirecting to /pool');
        $crawler = $client->clickLink('/pool');
        //Select the test character
        $crawler = $client->clickLink("Character Fixture");
        //Navigate to the edit page of the test character
        $crawler = $client->clickLink("Edit");
        //Fill the form to edit the character test
        $formUpdatepdateCharacterChoice = $crawler->selectButton("Submit")->form();
        $formUpdatepdateCharacterChoice["character_choice[tier]"]->select("D-");
        $crawler = $client->submit($formUpdatepdateCharacterChoice, [
            "character_choice[name]" => "Still a Fixture",
        ]);
        //Redirect to the characterDetail page
        $this->assertSelectorTextContains('title', 'Redirecting to ');
        $crawler = $client->click($crawler->filter('a')->link());
        //Assert that the character has been updated
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(".descriptionCharacterChoiceContainer h1", "Still a Fixture");
        $this->assertSelectorTextContains(".descriptionCharacterChoiceContainer", "D-");

    }

    public function testCharacterChoiceDelete():void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        //Login with admin user
        $testUser = $userRepository->findOneBy(["email"=>"q-detre@sfi.fr"]);
        $client->loginUser($testUser);
        $this->assertNotNull($testUser, 'The test admin is connected');
        $crawler = $client->request('GET', '/pool');
        //Navigate to the test character detail
        $crawler = $client->clickLink("Still a Fixture");
        //Delete the character
        $deleteCharacterForm = $crawler->selectButton("Delete")->form();
        $crawler = $client->submit($deleteCharacterForm);
        //Navigate to the pool
        $this->assertSelectorTextContains('title', 'Redirecting to /pool');
        $crawler = $client->clickLink('/pool');
        //Assert that the character has been removed from pool
        $this->assertSelectorTextNotContains('#gridCharacterCp', "Still a Fixture");
        //Navigate to the CharacterChoice
        $crawler = $client->clickLink('Add a character');
        //Assert that the test character no longer exist
        $this->assertSelectorTextNotContains("#gridCharacterChoice", "Character Fixture");
    }
}
