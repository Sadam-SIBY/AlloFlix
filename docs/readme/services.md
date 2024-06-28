# Services

Un service est une classe/un objet qui rempli un rôle.

Un service est une classe qui peut être instanciée à tout moment et à tout endroit ou presque du code source et fournie par Symfony à cet endroit.
    - La portion de code qui a besoin de ce service, par ex. `UserPasswordHasherInterface`, "dépend" de ce service, le service s'appelle donc aussi une "dépendance" et le fait de recevoir cet objet sans avoir besoin d'instancier nous-même, s'appelle **l'injection de dépendance**. Parce qu'on a pas écrit `new PasswordHasher();` on a pas instancié nous-même la dépendance.

Dans Symfony ces services sont *enregistrés* dans un "conteneur de services", mais *non instanciés*.
=> l'avantage est que ces classes seront instanciées à la demande et cela augmente nettement les performances du Framework/de l'application.

## Pour faire plus simple
Jusque là, sur Symfony, on a vu comment créer des controllers, des entités, des Repository, des Voters, etc ...
Mais si je veux coder une fonction qui fait quelque chose de précis, comme par exemple générer un message flash de Bienvenue, où est-ce que je dois le faire ?
### Là est l'intérêt des Services
Un Service c'est juste une classe PHP, qui va contenir des méthodes qu'on va coder nous même. C'est dedans que ça doit aller.
C'est pas censé communiquer avec un bdd(Ca c'est le rôles des entités / Repository), c'est pas censé créer une route(Ca c'est le rôles des Controller), c'est juste des classes avec des methodes qui servent a faire le reste.
### Comment on va s'en servir ?
On va créer un service qui génère des citations au hasard, et on va les afficher en message Flash en haut de notre site.
