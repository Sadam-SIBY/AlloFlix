<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Classe qui effectue des tests sur mon application en tant qu'utilisateur anonyme
 */
class AnonymousAccessTest extends WebTestCase
{
    /**
     * Fonction qui test une/des routes publique
     *
     * @dataProvider getUrlPublic
     */
    public function testPublic($url): void
    {
        // On créer un client (simulation : on se met dans la peau d'un client)
        $client = static::createClient();
        // Ici on va simuler avec le client une requête HTTP en méthode GET sur la route '/'
        // 1er param = la methode HTTP, 2eme param = la route
        $crawler = $client->request('GET', $url);
        // Ici on check si la requête a fonctionné
        // assertResponseIsSuccessful() check si le code status c'est 200 (ok)
        // doc : https://symfony.com/doc/5.x/testing.html#testing-application-assertions
        $this->assertResponseIsSuccessful();
        // On check si dans la reponse de la requête il y a une balise h1 qui contient 'Hello World'
        $this->assertSelectorTextContains('h1', 'Films, séries TV et popcorn en illimité.');
    }

    /**
     * Fonction qui test une/des routes privés
     *
     * @dataProvider getUrlPrivate
     */
    public function testPrivate($url): void
    {
        // On créer un client (simulation : on se met dans la peau d'un client)
        $client = static::createClient();
        // Ici on va simuler avec le client une requête HTTP en méthode GET sur la route '/'
        // 1er param = la methode HTTP, 2eme param = la route
        $crawler = $client->request('GET', $url);
        // Ici on check si la requête a fonctionné, on check surtour si on a affaire a une redirection
        $this->assertResponseRedirects();
        // On check si dans la reponse de la requête il y a une balise h1 qui contient 'Hello World'
        $this->assertSelectorTextContains('body', 'Redirecting');
    }

    /**
     * Provider de routes à tester en tant qu'utilisateur anonyme sur ce qui est censé être plublique
     *
     * @return void
     */
    public function getUrlPublic()
    {
        // yield est une fonction qui sert à génerer plein d'urls qu'on va recuperer ailleurs dans la classe
        yield['/'];
        yield['/genre/5'];
        // yield['/movie/show/seven-pounds'];
        // yield['/favorites/list'];
        // Toutes les routes du front ...
    }

    /**
     * Provider de routes à tester en tant qu'utilisateur anonyme sur ce qui est censé être privé
     *
     * @return void
     */
    public function getUrlPrivate()
    {
        // yield est une fonction qui sert à génerer plein d'urls qu'on va recuperer ailleurs dans la classe
        yield['/movie/show/seven-pounds'];
        yield['/favorites/list'];
        // Toutes les routes du front ...
    }
}
