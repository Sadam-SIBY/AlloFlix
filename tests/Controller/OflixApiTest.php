<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class OflixApiTest extends ApiTestCase
{
    public function testGetMovies(): void
    {
        // Ici on créer un client qui va directement effectuer une requete HTTP en methode GET sur cette route => '/api/movies'
        $response = static::createClient()->request('GET', '/api/movies');
        // Ici on check si la reponse est bonne (code status commence par 2XX)
        $this->assertResponseIsSuccessful();
        // Recuper le contenu JSON de la requete qu'on stock dans $content
        $content = $response->getContent();
        // On converti le json en tableau associatif PHP grace a json_decode
        $result = json_decode($content, true);
        // Maintenant $result est un tableau associatif PHP qui contient le retour de la requête
        dump($result);
        // Je check si a la case 19 (20eme itération) j'ai bien une propriété "id"
        $this->assertArrayHasKey('id', $result[19], "Pas de id dans le tableau");
        // Si oui, alors on a bien passé le test

        // $this->assertJsonContains(['title' => 'Seven Pounds']);
    }

}