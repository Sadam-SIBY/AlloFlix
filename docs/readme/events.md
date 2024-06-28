# Les évènements Symfony
Les évènements (les events en anglais)est un principe qui existe dans le Front-End (en Javascript), mais aussi en Back-End (avec Symfony par exemple).
En Javascript, les évènements se déclenche coté client, c'est à dire que les events en js se déclenchent quand l'utilisateur aura des interactions avec le site web.
Comme par exemple au clic d'un bouton => c'est un évènement qui se déclenche.
Du coup, quoiqu'il arrive, l'évenement va se déclencher, mais nous on aura la possibilité d'agir en conséquence => on pourra dire que si tel évènement se déclenche, alors on pourra effectuer tel action.
#### Exemple :
Si un client clique sur un bouton "Afficher toto", en JS on pourra "se mettre sur écoute de cet évènement" et agir dès que cet évènement se déclenche, on fera en sorte d'afficher une image de toto si le client clique sur ce bouton.
Donc ici l'évenement c'est le clic du bouton "Afficher toto" et le handler (la fonction qui s'execute au déclenchement de l'evenement) c'est la fonction qui va afficher l'image de toto.
## Et Symfony dans tous ça ?
Et bien c'est exactement le même principe sur les évènements Symfony, sauf que Symfony c'est un framework Back-End, donc on va gérer les évenement qui se passent sur Symfony.
Sur Symfony, il y a 3 types d'évenements :
1. Les évènements de type Kernel
2. Les évènements de type Form
3. Les évènements de type Doctrine

## Les évènements Kernel
Doc : https://symfony.com/doc/current/reference/events.html
Pour Symfony (comme pour tous en informatique), il y a ce qu'on appelle un "Kernel".
Un Kernel c'est le coeur d'une technologie. Doc ici le Kernel de Symfony c'est le coeur de Symfony, c'est à dire que c'est le point d'entrée de toute application Symfony et il gère plusieurs choses qui peuvent avoir l'air basique comme par exemple :
- L'initilisation de l'application => géré par le kernel
- Les requêtes HTTP entrantes et sortantes => gérés par le kernel
- La gestion des contrôleurs => gérés par le kernel
- La gestion des évènements => géré par le kernel
- Et encore pleins d'autres

Voici la liste des évenements de type Kernel :
- kernel.request : Evenement déclenché lorsque le Kernel reçoit une requête HTTP entrante.
- kernel.controller : Evenement déclenché juste avant que le kernel appelle le contrôleur responsable de la gestion d'une requête
- kernel.controller_arguments : Evenement déclenché apres que le contrôleur a été instancié et avant qu'il soit appelé
- kernel.view : Evenement déclenché après que le contrôleur renvoie sa réponse, mais pas sous forme d'objet Response
- kernel.response : Evenement déclenché juste avant que le kernel envoie un objet Response au client
- kernel.finish_request : Evenement déclenché à la fin du traitement d'une requête
- kernel.terminate : Evenement déclenché juste apres que le kernel a finit d'envoyer sa réponse au client
- kernel.exception : Evenement déclenché lorsque l'applciation rencontre une exception (un erreur Symfony).
IMPORTANT : Comprenez que jusque là, ces évènements se produisaient, ils se produisent toujours sur notre app Symfony, mais seulement là on va voir comment gérer ces évènements.

## Les évènements Formulaire
Doc : https://symfony.com/doc/current/form/events.html

Les évènements de type Form (sur Symfony) sont des évènements qui se déclenchent lors du cycle de vie d'un formulaire Symfony. Ils permettent d'intercépter et de modifier le comportement d'un Formulaire à ses différents cycles de vie.
Voici les types d'évènements de Form :
- PRE_SET_DATA : Délcenché lorsque le formulaire est initialisé
- POST_SET_DATA : Déclenché lorsque les données du modèle ont été ajoutés au formulaire.
- PRE_SUBMIT : Déclenché avant que le formulaire ne soit soumit et que les données envoyés par l'utilisateur soient liés au formulaire.
- SUBMIT : Déclenché lorsque le formulaire est soumit et avant que les données ne soient traités.
- POST_SUBMIT : Déclenché apres le traitement du formulaire.

## Les évènements de type Doctrine
Doc : https://symfony.com/doc/current/doctrine/events.html

Les évènements Doctrine sont des évènements déclenchés lors d'opérations spécifiques effectués avec le composant ORM Doctrine. Ces évènements permettent d'intercepter et de réagir aux actions effectué sur les entités et les objets gérés par Doctrine. Ils offrent une flexibilité et une extensibilité supplémentaire pour personnaliser le comportement de Doctrine en réponse à des actions spécifiques.
Quelques évènements Doctrine (pas tous car il y en a beaucoup) :
- prePersist : Déclenché avant qu'une nouvelle entité soit persist.
- postPersist : Déclenché apres qu'une nouvelle entité soit persist.
- preUpdate : Déclenché avant que la maj d'une entité.
- postUpdate : Déclenché juste apès qu'une entité soit mise à jour.
- preRemove : Déclenché avant qu'une entité soit supprimé.
- postRemove : Déclenché apès qu'une entité soit supprimé.

Liste plus complète ici : https://www.doctrine-project.org/projects/doctrine-orm/en/current/reference/events.html#events-overview