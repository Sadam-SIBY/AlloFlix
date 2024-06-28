<?php


namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        // On creer un client
        $client = static::createClient();
        // Ici on veut s'authentifier
        // On recupere le repository des User
        $userRepository = static::getContainer()->get(UserRepository::class);
        // recupere l'utilisteur que je veux tester => ici user@user.fr
        $testUser = $userRepository->findOneByEmail('user@user.fr');
        // simuler l'auth à $testUser
        $client->loginUser($testUser);
        // Maintenant je suis authentifié en tant que 'user@user.fr'
        // Ici je veux tester cette route en tant que user 'user@user.fr'
        $crawler = $client->request('GET', '/review/20/add');

        // Ici je check si le retour est le bon
        $this->assertResponseStatusCodeSame(200); // Verifie si le code status recu c'est bien le code 200
        // $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Ajouter une critique');

        // On a bien acces a la page, maintenant on va remplir le form
        // on selectionne le button submit
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        // on recupere le Form object qui possede le bouton submit recupéré juste avant
        $form = $buttonCrawlerNode->form();
        // on va donner des valeurs a notre formulaire
        $form['review[username]'] = 'Fabien'; // username de la personne qui critique
        $form['review[email]'] = 'toto@toto.fr'; // email de la personne qui critique
        $form['review[content]'] = 'totototototototototototo'; // contenu de la critique
        $form['review[rating]'] = 5; // note de la personne
        // Reactions est un tableau
        $form['review[reactions]'] = ["smile", "cry"];
        $form['review[watchedAt]'] = '2024-01-23 00:00:00'; // date ou on a vu le film
        // submit the Form object
        $client->submit($form);
    }
}
