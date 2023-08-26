<?php

namespace App\Tests\Controller;

use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    use BaseDatabaseTrait;
    public function testWrongCredentials():void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'SSBU Pool');

        $crawler = $client->clickLink('Login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('form h1', 'Please sign in');

        $logginButton = $crawler->selectButton('Sign in');

        $form = $logginButton->form();
        $form['email'] = 'q-detre@sfi.fr';
        $form['password'] = 'notThePassword';

        $crawler = $client->submit($form);

        $this->assertSelectorTextContains('title', 'Redirecting to /login');
        $crawler = $client->clickLink('/login');

        $this->assertSelectorTextContains('form div', 'Invalid credentials.');
    }
    public function testRightCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'SSBU Pool');

        $crawler = $client->clickLink('Login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('', 'Please sign in');

        $logginButton = $crawler->selectButton('Sign in');

        $form = $logginButton->form();
        $form['email'] = 'q-detre@sfi.fr';
        $form['password'] = 'Passw0rd';
        $crawler = $client->submit($form);

        $this->assertSelectorTextContains('title', 'Redirecting to /pool');
        $crawler = $client->clickLink('/pool');
    }

    public function testRegister():void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'SSBU Pool');

        $crawler = $client->clickLink('Register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('', 'Register');

    }

    public function testRegisterAlreadyUsedEmail():void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'SSBU Pool');

        $crawler = $client->clickLink('Register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('', 'Register');

        $formRegister = $crawler->selectButton('Register')->form();
        $crawler = $client->submit($formRegister, [
            'registration_form[email]' => 'q-detre@sfi.fr',
            'registration_form[username]' => 'test',
            'registration_form[name]' => 'Test',
            'registration_form[surname]' => 'Ostérone',
            'registration_form[plainPassword]' => 'Passw0rd',
        ]);


        $this->assertSelectorTextContains('form div ul', 'There is already an account with this email');

    }
}
