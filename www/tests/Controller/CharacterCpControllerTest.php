<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class CharacterCpControllerTest extends WebTestCase
{
    use BaseDatabaseTrait;
    public function testCharacterCpRedirectToCharacterDetail():void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        //Login with admin user
        $testUser = $userRepository->findOneBy(["email" => "q-detre@sfi.fr"]);
        $logged = $client->loginUser($testUser);
        $this->assertNotNull($logged, 'The test admin is connected');

        $crawler = $client->request('GET', '/pool');

        $this->assertSelectorTextContains('', 'Hero');

        $crawler = $client->clickLink('Hero');

        //Assert that we can access the correct character by clicking on it
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.characterChoiceContainer h1', 'N°72');
    }

    public function testAddCharacterCp():void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        //Login with admin user
        $testUser = $userRepository->findOneBy(["email" => "q-detre@sfi.fr"]);
        $logged = $client->loginUser($testUser);
        $this->assertNotNull($logged, 'The test admin is connected');

        $crawler = $client->request('GET', '/pool');

        //Test if we can access the page to add a character
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('', 'Add a character');

        $crawler = $client->clickLink('Add a character');

        //Test if we can add a character as a characterCp by adding "Mario"
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.fighterCard', 'Mario');
        $addCharacterBtn = $crawler->selectButton('Mario');
        $formNewCharacter = $addCharacterBtn->form();
        $crawler = $client->submit($formNewCharacter);

        $this->assertSelectorTextContains('title', 'Redirecting to /pool');
        $crawler = $client->clickLink('/pool');

        //Assert that we successfully added "Mario" as a characterCp
        $this->assertResponseIsSuccessful();
        //$this->assertSelectorTextContains('.fighterCard p', 'Mario');
        $this->assertTrue($crawler->filter('a.fighterCard')->eq(2)->children()->eq(1)->text() === "Mario");
    }
    public function testDeleteCharacterCp():void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        //Login with admin user
        $testUser = $userRepository->findOneBy(["email" => "q-detre@sfi.fr"]);
        $logged = $client->loginUser($testUser);
        $this->assertNotNull($logged, 'The test admin is connected');

        $crawler = $client->request('GET', '/pool');

        $deleteMarioBtn = $crawler->filter('a.fighterCard')->eq(2)->children()->eq(2)->selectButton('Delete');
        $formDeleteMario = $deleteMarioBtn->form();

        $crawler = $client->submit($formDeleteMario);

        $this->assertSelectorTextContains('title', 'Redirecting to /pool');
        $crawler = $client->clickLink('/pool');

        $this->assertTrue($crawler->filter('a.fighterCard')->eq(2)->text() === "Add a character");

        //Assert that we can't access a characterCp we don't have in our pool
        $crawler = $client->request("GET", "/character/number/01");

        $this->assertSelectorNotExists('.characterChoiceContainer h1');
        $crawler = $client->back();

        $this->assertSelectorTextContains("","Hello QuenDetr");
        $this->assertSelectorTextContains("#gridCharacterCp", "Hero");
        $this->assertSelectorTextNotContains("#gridCharacterCp", "Mario");
    }
}
