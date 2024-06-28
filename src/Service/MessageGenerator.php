<?php
// src/Service/MessageGenerator.php
namespace App\Service;

class MessageGenerator
{
    /**
     * Fonction qui retourne une citation de Kaamelott au hasard
     *
     * @return string
     */
    public function getRandomMessage(): string
    {
        // Liste de 3 citations de Kaamelott
        // Voir : https://www.kaakook.fr/film-1343
        $messages = [
            'Le gras c’est la vie !',
            'Moi, je m’en fous, si on me force à y retourner, je retiens ma respiration jusqu’à ce qu’on arrête de me forcer à y retourner.',
            'Oh vous, toujours vous, mais allez chier dans une fiole, on verra après.',
        ];
        // $index sera egal a index aléatoire dans le tableau $messages
        $index = array_rand($messages);
        // On retourne le message qui apour index $index
        return $messages[$index];
    }
}