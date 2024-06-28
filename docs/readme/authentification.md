# Sécurité : l'authentification et l'autorisation sur O'Flix

## Ca évoque quoi ?
Qu'il y aura des utilisateurs sur notre site.
## Où vont être stockés nos utilisateurs ?
En BDD (dans une table user)
## Ce qui veut dire que ...
On va devoir créer une entité User !
## Mais attention, cette entité User est un peu particulière, on va pas la construire comme d'habitude
En effet, pour créer cette entité User, on va se servir d'un composant symfony qui va se charger non seulement de créer cet entité User, mais en plus va mettre en place toute la partie authentification/sécurité.
Ce composant va aussi nous permettre de mettre en place les différents rôles sur les utilisateurs (Role admin, Role lambda, etc).
On va aussi pouvoir mettre en place les identifiants de connexion (email + mdp).
On va aussi mettre en place le Register (la création d'un compte) ainsi que la deconnexion => les formulaire d'inscription, de login aussi.
## Comment s'y prendre ?
Voir la doc : https://symfony.com/doc/current/security.html
### Etape 1 : On va installer le composant qui va nous faciliter le travail : Le security-bundle
```bash
composer require symfony/security-bundle
```
### Etape 2 : On va créer l'entité User + faire en sorte que cette entité User est l'entité qui fait référence à l'utilisateur de l'app
```bash
php bin/console make:user
# Le nom de l'entité => ici User
 The name of the security user class (e.g. User) [User]:
 > User
# Est-ce que qu'on veut stocker les données des utilisateurs dans la BDD ? => yes
 Do you want to store user data in the database (via Doctrine)? (yes/no) [yes]:
 > yes
# La propriété qui fait référence à l'identifiant de connexion => l'adresse mail
 Enter a property name that will be the unique "display" name for the user (e.g. email, username, uuid) [email]:
 > email
# Est-ce qu'on active le hashage du mot de passe ? => yes
 Will this app need to hash/check user passwords? Choose No if passwords are not needed or will be checked/hashed by some other system (e.g. a single sign-on server).

 Does this app need to hash/check user passwords? (yes/no) [yes]:
 > yes
# Conséquences = Ca a crées l'entité User et son Repository, + MAJ du security.yaml
 created: src/Entity/User.php
 created: src/Repository/UserRepository.php
 updated: src/Entity/User.php
 updated: config/packages/security.yaml
```
Voyons voir cette entité User :
```php
// src/Entity/User.php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    private string $password;

    // ... tous les Getter et Setter
}
```
Dans cette entité, on va avoir 4 propriétés :
- $id = Chaque entité à un champ $id, rien de nouveau.
- $email = L'email de l'utilisateur, et ici, c'est l'identifiant de connexion. Au format string
- $roles = Un array (un tableau) au format JSON. Tableau dans lequel on va avoir tous les roles de l'utilisateur (ROLE_ADMIN, ROLE_USER, etc) qui vont permettre de définir les droits.
- $password = Le mot de passe de l'utilisateur. Au format string.
Note : Pour ajouter des champs en plus dans mon entité User, j'ai juste à taper cette commande : 
```bash
# Comme dhab ...
php bin/console make:entity User
```
### Etape 3 : On migre !
Maintenant on a une nouvelle entité User.php, il nous reste plus qu'a migrer.
```bash
# On créer le fichier de migration
php bin/console make:migration
# On execute les migrations
php bin/console doctrine:migrations:migrate
```
Maintenant, si on check notre BDD sur Adminer, on peut vvoir que la table user a bien été créée.

## Création des controller pour le Login (routes pour le login, logout), l'inscription (le register), les formaulaires, etc
### Etape 1 : Créer un controller pour le login
Voir : https://symfony.com/doc/4.x/security/form_login_setup.html
```bash
# Commande qui construit l'authentification
php bin/console make:auth
# Choix du style d'authentification : 
# [0] => Vide (pas d'authentification, donc a nous de touus construire)
# [1] => L'authentification via un form (deja crée)
# On choisit 1
What style of authentication do you want? [Empty authenticator]:
 [0] Empty authenticator
 [1] Login form authenticator
> 1
# Le nom de la classe de l'authentificateur, la classe ou il y aura les methodes de verification pour la connexion
# Pour faire simple, c'est dans cette classe que l'authentification va se faire
The class name of the authenticator to create (e.g. AppCustomAuthenticator):
> LoginFormAuthenticator
# Choix du nom du controller pour le Login (Le login et pas le Register, c'est pour un peu plus tard)
Choose a name for the controller class (e.g. SecurityController) [SecurityController]:
> SecurityController
# Est-ce qu'on veut generer la route pour se deconnecter ('/logout') => yes
Do you want to generate a '/logout' URL? (yes/no) [yes]:
> yes

# Ca nous créer 
# src/Security/LoginFormAuthenticator.php => Classe qui gere l'authentification 
# config/packages/security.yaml => Ajout dans le secuity.yaml
# src/Controller/SecurityController.php => Controller ou on aura nos routes /login et /logout
# templates/security/login.html.twig => la vue ou on aura le formulaire pour se connecter
 created: src/Security/LoginFormAuthenticator.php
 updated: config/packages/security.yaml
 created: src/Controller/SecurityController.php
 created: templates/security/login.html.twig
```
### Etape 2 : Checker le controller SecurityController
```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * Page de Login
     */
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // L'objet $authenticationUtils nous permet d'avoir des informations sur l'authentification
        // C'est un peu le meme délire que l'objet Request $request
        // On avait besoin de $request pour avoir des informations sur la requête
        // Et la on a besoin de $authenticationUtils pour avoir des informations sur l'authentification

        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        // On stock dans $error le message d'erreur si il y a une erreur quand on se connecte (user inexistant, mauvais mdp, etc)
        $error = $authenticationUtils->getLastAuthenticationError(); // Sert a récupérer le dernier message d'erreur
        // last username entered by the user
        // On stock dans $lastUsername le dernier username entré (ici notre identifiant de connexion c'est l'email)
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
```
Dans ce controller, on a 2 méthode : logout() et login()
#### login()
```php
/**
* Page de Login
* Route de la methode '/login'
*/
#[Route(path: '/login', name: 'app_login')] 
public function login(AuthenticationUtils $authenticationUtils): Response
{
    // L'objet $authenticationUtils nous permet d'avoir des informations sur l'authentification
    // C'est un peu le meme délire que l'objet Request $request
    // On avait besoin de $request pour avoir des informations sur la requête
    // Et la on a besoin de $authenticationUtils pour avoir des informations sur l'authentification

    // if ($this->getUser()) {
    //     return $this->redirectToRoute('target_path');
    // }

    // get the login error if there is one
    // On stock dans $error le message d'erreur si il y a une erreur quand on se connecte (user inexistant, mauvais mdp, etc)
    $error = $authenticationUtils->getLastAuthenticationError(); // Sert a récupérer le dernier message d'erreur
    // last username entered by the user
    // On stock dans $lastUsername le dernier username entré (ici notre identifiant de connexion c'est l'email)
    $lastUsername = $authenticationUtils->getLastUsername();
    // On retourne la vue login qui affiche le formulaire pour se connecter
    return $this->render('security/login.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error
    ]);
}
```
La page de login :
![Texte alternatif](images/login.png "Screenshot page de Login")
Sauf que la on a aucun utilisateur de créée, et on ne va pas en créer via Adminer.
### Etape 3 : Créer le Register (l'inscription, la création d'un compte)
Voir : https://symfony.com/doc/current/security.html#registering-the-user-hashing-passwords
Pour créer le Register on va par le maker de Symfony
```bash
# Commande pour créer le Register
php bin/console make:registration-form
 Creating a registration form for App\Entity\User
# Faire en sorte que l'email (identifiant de connexion) soit unique (impossible que 2 users aient la meme adresse mail ?) => yes
 Do you want to add a #[UniqueEntity] validation attribute to your User class to make sure duplicate accounts aren't created? (yes/no) [yes]:
 > yes
#  Envoyer un mail de verification a l'utilisateur (pourquoi pas ? mais pas pour l'instant) => donc no (on verra plus tard)
 Do you want to send an email to verify the user's email address after registration? (yes/no) [yes]:
 > no
#  Est-ceque je veux que juste apres avoir créer un compte l'utilisateur soit authentifié ? => yes
 Do you want to automatically authenticate the user after registration? (yes/no) [yes]:
 > yes

 updated: src/Entity/User.php
 created: src/Form/RegistrationFormType.php
 created: src/Controller/RegistrationController.php
 created: templates/registration/register.html.twig
```
#### Que s'est-il passé ?
1. Ca a mit a jour l'entité User, plus précisément ca a ajouter un parametre ```unique:true``` à la propriété email dans l'entité User
```php
// unique: true => rend l'email unique (impossible que 2 user aient le meme email)
#[ORM\Column(length: 180, unique: true)]
private ?string $email = null;
```
2. Ca a crée un formulaire Form/RegistrationFormType.php
```php
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }
//...
}
```
3. Ca créer le controller RegistrationController.php
```php
class RegistrationController extends AbstractController
{
    /**
     * Methode qui authentifie l'utilisateur
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        // On créer une instance de User() car ici on veut créer un user
        $user = new User();
        // Construit un $form qui tourne autour de $user
        $form = $this->createForm(RegistrationFormType::class, $user);
        // On passe les infos de la requete au $orm (pour savoir s'il a été soumit ou non)
        $form->handleRequest($request);
    // On check si le form a été soumit et si il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            // Ici on hache le mot de passe
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // On persist et flush $user
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            // Ici on authentifie l'utilisateur
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }
        // Si le form n'est pas submit, alor son retourne la vue qui affiche le formulaire
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
```
4. Ca a créer la vue Twig qui affihe le formulaire d'inscription :
```twig
{% extends 'base.html.twig' %}

{% block title %}Register{% endblock %}

{% block main %}
    <h1>Register</h1>
    <!-- On découpe l'affichage du form -->
    {{ form_errors(registrationForm) }}

    {{ form_start(registrationForm) }}
        {{ form_row(registrationForm.email) }}
        {{ form_row(registrationForm.plainPassword, {
            label: 'Password'
        }) }}
        {{ form_row(registrationForm.agreeTerms) }}

        <button type="submit" class="btn">Register</button>
    {{ form_end(registrationForm) }}
{% endblock %}
```
Maintenant si on créer un utilisateur, voyons comment ca se passe sur la route '/register' :
![Texte alternatif](images/register1.png "Screenshot page de Register")
On met un email, un password (au moins 6 caractères),et ... erreur !
![Texte alternatif](images/register_error.png "Screenshot page de Register error")
L'erreur me dit que dans src/Security/LoginFormAuthenticator.php je dois modifier le lien de redirection lorsque l'utilisateur est authentifié
On va donc dans src/Security/LoginFormAuthenticator.php :
```php
/**
* Methode qui s'execute quand l'authentification se passe bien
*/
public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
{
    if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
        return new RedirectResponse($targetPath);
    }

    // return new RedirectResponse($this->urlGenerator->generate('main_home'));
    throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
}
```
On modifie la fonction pour mettre en place la route qu'on veut afficher quand on est authentifié :
```php
/**
* Methode qui s'execute quand l'authentification se passe bien
*/
public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
{
    if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
        return new RedirectResponse($targetPath);
    }

    // Avant on utilisait :
    // return $this->redirectToRoute('nom_de_route') mais on est pas dans un controller donc pas de AbstractController
    // RedirectResponse ci dessous fonctionne exactement comme $this->redirectToRoute
    return new RedirectResponse($this->urlGenerator->generate('main_home'));
    //throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
}
```
On reteste (soit on s'authentifie, soit on se REcréer un compte) et ... tadaa, on est bien connecté et redirigé vers le main de O'Flix (la page d'accueil)
![Texte alternatif](images/register2.png "Screenshot page de Register")


## Rôles

On a mit en place l'authentification sur O'FLix => Fait !
Maintenant l'authentification, le login, le logout et le register sont fait.
Lorsque je me penche sur mon entité User (pour rappel, l'entité User c'est l'entité qui fait reference au User de connexion), c'est cette entité qui réprense l'utilisateur de connexion dans notre app car c'est ce qui est noté dans security.yaml
```yaml
providers:
    # used to reload user from session & other features (e.g. switch_user)
    app_user_provider:
        entity:
            # L'entité qui represente l'User dans notre application
            class: App\Entity\User
            # L'identifiant de connexion => ici email
            property: email
```
Dans notre entité User, on a plusieurs propriétés :
```php
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column]
private ?int $id = null;
// unique: true => rend l'email unique (impossible que 2 user aient le meme email)
#[ORM\Column(length: 180, unique: true)]
private ?string $email = null;
#[ORM\Column]
private array $roles = [];
/**
 * @var string The hashed password
 */
#[ORM\Column]
private ?string $password = null;
```
1. $id = l'id de l'utilisateur
2. $email = l'email de l'utilisateur (unique, pas de doublon possible)
3. $roles = le/les rôles d'un utilisateur
4. $password = mot de passe de l'utilisateur
Il y a une de ces propriétés qui va retenir notre attention, c'est la propriété roles.
Cette propriété, comme dit précedemment, c'est un tableau qui va contenir les rôles qu'un utilisateur peut avoir sur notre site.
Exemple :
imed@oclock.fr => ROLE_USER
L'utilisateur qui a pour email imed@oclock.fr aura le role ROLE_USER ce qui signifie que c'est un utilisateur classique.
Il y aura 3 types de rôles sur notre site :
1. ROLE_USER = Utilisateur classique, peut se balader dans le front de notre site
2. ROLE_ADMIN = Administrateur du site, peut se balader dans TOUTES les routes du site (front, backoffice)
3. ROLE_MANAGER = Manager du site, peut faire plus que le ROLE_USER (se balader dans le front) mais n'a pas tous les droits du ROLE_ADMIN, il ne peut pas aller dans tous le backoffice
## Maintenant on va restreindre l'accès au backoffice à tous les utilisateurs qui n'ont pas le role ROLE_ADMIN

### 1ere étape : on va créer 3 user via les fixtures
Dans src/DataFixtures/AppFixtures.php on créer 3 utilisateurs : 1 ROLE_USER, 1 ROLE_ADMIN et 1 ROLE_MANAGER
```php
// On créer 3 utilisateurs
// 1er : utilisateur admin
$user = new User(); // On créer l'user
$user->setEmail("admin@admin.fr"); // On lui donne un email
$user->setRoles(['ROLE_ADMIN']); // On donne le role admin a cet user
$user->setPassword(password_hash("okokok",PASSWORD_BCRYPT));
$manager->persist($user); // On persis
// 2eme : utilisateur manager
$user = new User(); // On créer l'user
$user->setEmail("manager@manager.fr"); // On lui donne un email
$user->setRoles(['ROLE_MANAGER']); // On donne le role manager a cet user
$user->setPassword(password_hash("okokok",PASSWORD_BCRYPT));
$manager->persist($user); // On persis
// 3eme : utilisateur user (classique)
$user = new User(); // On créer l'user
$user->setEmail("user@user.fr"); // On lui donne un email
$user->setRoles(['ROLE_USER']); // On donne le role user a cet user
$user->setPassword(password_hash("okokok",PASSWORD_BCRYPT));
$manager->persist($user); // On persis
$manager->flush();
```

Une fois que c'est fait, on charge les fixtures :
```bash
php bin/console doctrine:fixtures:load
```
Et on check en BDD ce que ca donne :
![Texte alternatif](images/users_bdd.png "Screenshot page de Users")
On a bien nos 3 users, avec les 3 rôles différents de créées !
### Etape 2 : On va restreindre l'accès au backoffice à tous les utilisateurs qui n'ont pas le rôle ROLE_ADMIN
Comme on peut le voir dans le screen de l'étape précédente, il n'y a que l'utilisateur qui a pour email admin@admin.fr qui a le rôle ROLE_ADMIN.
On va donc faire en sorte qu'il n'y ait que cet utilisateur qui ait accès aux routes qui commencent par '/back'
#### Comment s'y prendre ?
Voir la doc : https://symfony.com/doc/current/security.html#securing-url-patterns-access-control
On se rend dans config/packages/security.yaml :
```yaml
# ...
access_control:
        # - { path: ^/admin, roles: ROLE_ADMIN }
        # - { path: ^/profile, roles: ROLE_USER }
# ...
```
Voilà ce qu'on a de base, on va dans la partie access_control car c'est a ou on doit définir les droits d'accès à certaines routes.
La syntaxe est hyper simple :
```yaml
# { path: ^/par_quoi_va_commencer_la_route, roles: ROLE_AUTORISEES_A_ACCEDER_A_LA_ROUTE }
- { path: ^/admin, roles: ROLE_ADMIN }
```
Sachant que les routes du back commencent toutes par '/back', admettons qu'on veuille faire en sorte que les routes qui commencent par '/back' ne soient accessible que par les utilisateurs ROLE_ADMIN :
```yaml
- { path: ^/back, roles: ROLE_ADMIN }
```
Une fois que c'est fait, on va tester.
On se connecte en tant qu'utilisateur qui a le ROLE_ADMIN (donc admin@admin.fr) et on essaye d'accéder a cette route :
![Texte alternatif](images/admin_access.png "Screenshot page de Users")
Et voilà, on a bien accès à la route qui commence par '/back'.
Testons maintenant avec l'utilistauer ROLE_USER, normalement on ne devrai pas avoir accès à cette route :
![Texte alternatif](images/user_access.png "Screenshot page de Users")
Comme prévue, je n'ai pas accès à cette route en tant que ROLE_USER.
### Si je veux donner l'accès au backoffice à l'utilisateur ROLE_MANAGER
Pour donner l'accès au backoffice à l'utilisateur ROLE_MANAGER, je vais ajouter quelque chose au security.yaml :
```yaml
- { path: '^/back', roles: [ROLE_ADMIN, ROLE_MANAGER] }
```
## Hierarchie des droits
Voir : https://symfony.com/doc/current/security.html#hierarchical-roles
On va ajouter des restrictions sur la page de detail d'un film et sur la page des favoris.
```yaml
- { path: '^/favorites', roles: [ROLE_USER, ROLE_ADMIN, ROLE_MANAGER] }
- { path: '^/movie', roles: [ROLE_USER, ROLE_ADMIN, ROLE_MANAGER] }
```
On a vu comment attribuer des droits sur certains utilisateurs, maintenant on va voir comment faire en sorte de "hierarchiser les droits".
#### Ca veut dire quoi hierarchiser les droits ?
Ca sert à dire que si ROLE_USER à accès à une route, nulle besoin de dire que ROLE_ADMIN a accès à cette route, cela va de soi.
#### Mais comment s'y prendre ?
Toujours dans le security.yaml :
```yaml
role_hierarchy:
    # ROLE_ADMIN:       # A LES TOUS LES DROITS DU ROLE_USER
    ROLE_ADMIN:       ROLE_USER
    ROLE_ADMIN:       ROLE_MANAGER
    ROLE_MANAGER:     ROLE_USER
```
Ci dessus, on dit que ROLE_ADMIN a tous les droits du ROLE_USER et de ROLE_MANAGER (et c'est tout).
Si on veut écourter tout ça, on peut juste dire que ROLE_ADMIN a les droits de ROLE_MANAGER et ROLE_MANAGER a les droits de ROLE_USER :
```yaml
role_hierarchy:
    # ROLE_ADMIN A TOUS LES DROITS DU ROLE_MANAGER qui lui a tous les droits du ROLE_USER (hierarchie)
    ROLE_ADMIN:       ROLE_MANAGER
    ROLE_MANAGER:     ROLE_USER
```
## Checker les rôles de l'utilisateur via Twig
Doc : https://symfony.com/doc/current/security.html#access-control-in-templates
Pour checker si un utilisateur est admin, il suffit de faire :
```php
// Rentre dans le if si l'user a le rôle ROLE_ADMIN
{% if is_granted('ROLE_ADMIN') %}
    <a href="...">Delete</a>
{% endif %}
```

Voila ce qu'on va mettre dans base.html.twig pour afficher 'Backoffice' que si l'utilisateur a les droits ROLE_MANAGER :
```php
{# On check si l'utilisateur a les droits ROLE_MANAGER ,car si il a les droits ROLE_MANAGER, c'est que c'est soit le manager soit l'admin (car l'admin a tous les droits du manager) #}
{# is_granted() verifie si un utilisateur a le rôle donnée en parametre, ici ROLE_MANAGER #}
{% if is_granted('ROLE_MANAGER') %}
    {# Si il a les droits du MANAGER, alors il aura ccès au Backoffice, on l'affiche donc #}
    <li><a class="dropdown-item" href="{{ path('main_back') }}">Backoffice</a></li>
    <li>
{% endif %}
```
## Petite restriction en plus
On va faire en sorte que les manager ait accès à tous les CRUD SAUF le crud sur User.
En gros il ne doit y avoir que le ROLE_ADMIN qui a accès au CRUD sur les User
#### Comment s'y prendre ?
On va aller dans security.yaml et on va ajouter une restriction :
```yaml
access_control:
        # On place cette restriction avant la 2eme car on veut que ROLE_MANAGER n'ait pas accès aux route qui commencent par /back/user
        - { path: '^/back/user', roles: ROLE_ADMIN }
        # Ensuite, on pourra dire que les autres routes qui commencent par /back (les autres routes du backoffice) seront accessible par le ROLE_MANAGER (du plus précis au moins précis)
        # Toutes les routes qui commencent par /back seront accessible au ROLE_MANAGER 
        - { path: '^/back', roles: ROLE_MANAGER }
        # ROLE_ADMIN A LES DROITS DE ROLE_USER, DONC PAS BESOIN DE RAJOUTER ROLE_ADMIN DANS roles:
        - { path: '^/favorites', roles: ROLE_USER } 
        - { path: '^/movie', roles: ROLE_USER }
```
Les regles de restrictions s'écrivent de la plus précise à la moins précise, c'est pour cela qu'on écrit la restriction sur '^/back/user' (accessible que pour le ROLE_ADMIN) avant '^/back' (accessible pour le ROLE_ADMIN et le ROLE_MANAGER).
Donc la, ROLE_MANAGER n'a plus accès au crud sur les User ("/back/user"), on va donc ne plus afficher la ligne User dans le home CRUD en placant une condition :
```twig
<table class="table">
        <thead>
            <tr>
                <th>Nom de l'entité</th>
                <th>Liste</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Movie</td>
                <td><a href="{{ path("browse_movie") }}">Liste</a></td>
            </tr>
            {# Le CRUD sur les user n'est accessible qu'au ROLE_ADMIN #}
            {# Ca affichera User que si on est 'ROLE_ADMIN' #}
            {% if is_granted('ROLE_ADMIN') %}
            <tr>
                <td>User</td>
                <td><a href="{{ path("app_back_user_index") }}">Liste</a></td>
            </tr>
            {% endif %}
        </tbody>
    </table>
```