# Routes de l'application

| URL | Méthode HTTP | Contrôleur       | Méthode | Titre HTML           | Commentaire    |
| --- | ------------ | ---------------- | ------- | -------------------- | -------------- |
| `/` | `GET`        | `MainController` | `home`  | Bienvenue sur O'flix | Page d'accueil |

|Endpoint            | Méthode HTTP| Description                                | Retour   |
| -------------------|-------------|--------------------------------            |------    |
|`/api/movies`       | `GET`       |`Récupération de tous les films`            |`200`     |
|`/api/movies/{id}`  | `GET`       |`Récupération du film dont l'id est fourni` |`200`     |
|`/api/movies`       | `GET`       |`------------------------------`            |`200`     |
|`/api/movies`       | `GET`       |`------------------------------`            |`200`     |
|`/api/movies`       | `GET`       |`------------------------------`            |`200`     |
|`/api/movies`       | `GET`       |`------------------------------`            |`200`     |
|`/api/genres`       | `GET`       |`Récupération de tous les genres`           |`200`     |
|`/api/movies`       | `GET`       |`------------------------------`            |`200`     |
