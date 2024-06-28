# SQL
## Récupérer tous les films
```sql
SELECT * FROM movie
```
## Récupérer les acteurs et leur(s) rôle(s) pour un film donné (id du film = 14)
```sql
SELECT `person`.*, casting. role
FROM `person`
INNER JOIN `casting` ON `person`.`id` = `casting`.`person_id`
WHERE `casting`.`movie_id` = 14
```
## Récupérer les genres associés à un film donné (id du film = 14)
```sql
SELECT genre.*
FROM genre
INNER JOIN movie_genre ON movie_genre.genre_id = genre.id
WHERE movie_genre.movie_id = 14
```
## Récupérer les saisons associées à un film/série donné (id du film = 15)
```sql
SELECT *
FROM season
WHERE movie_id = 15;
```
## Récupérer les critiques pour un film donné (id du film = 15)
```sql
SELECT *
FROM review
WHERE movie_id = 15
```
## Récupérer les critiques pour un film donné, ainsi que le nom de l'utilisateur associé (id du film = 15)
```sql
SELECT review.*, user.nickname
FROM review
JOIN user ON review.user_id = user.id
WHERE review.movie_id = 18;
```
## Calculer, pour chaque film, la moyenne des critiques par film (en une seule requête)
```sql
SELECT movie.title, AVG(review.rating)
FROM movie
INNER JOIN review ON review.movie_id = movie.id
GROUP BY movie.id
```
### Pour un film donnée
```sql
SELECT movie.title, AVG(review.rating)
FROM movie
INNER JOIN review ON review.movie_id = movie.id
WHERE movie.id = 18;
```
## Récupérer tous les films pour une année de sortie donnée (2010)
```sql
SELECT *
FROM movie
WHERE YEAR(release_date) = '2010';
```
## Récupérer tous les films pour un tire donné (par ex. 'Epic Movie')
```sql
SELECT *
FROM movie
WHERE title = 'Epic Movie';
```
## Récupérer tous les films dont le titre contient une chaîne donnée
```sql
SELECT * 
FROM movie
WHERE title LIKE '%pirate%'
```