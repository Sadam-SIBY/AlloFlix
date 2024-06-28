-- Adminer 4.8.1 MySQL 10.3.38-MariaDB-0ubuntu0.20.04.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

INSERT INTO `genre` (`id`, `name`) VALUES
(1,	'Drame'),
(2,	'Thriller'),
(3,	'Fantastique'),
(4,	'Animation'),
(5,	'Boxe'),
(6,	'Science Fiction');

INSERT INTO `movie` (`id`, `title`, `duration`, `release_date`, `type`, `summary`, `synopsis`, `poster`, `rating`) VALUES
(1,	'Rocky',	120,	'2024-01-03',	'Film',	'Dans les quartiers populaires de Philadelphie, Rocky Balboa dispute de temps à autre, pour quelques dizaines de dollars, des combats de boxe sous l\'appellation de \"l\'étalon italien\".',	'Dans les quartiers populaires de Philadelphie, Rocky Balboa dispute de temps à autre, pour quelques dizaines de dollars, des combats de boxe sous l\'appellation de \"l\'étalon italien\". Cependant, Mickey, son vieil entraîneur, le laisse tomber. Pendant ce temps, Apollo Creed, le champion du monde de boxe catégorie poids lourd, recherche un nouvel adversaire pour remettre son titre en jeu. Son choix se portera sur Rocky.',	'https://fr.web.img4.acsta.net/c_310_420/pictures/21/11/16/15/01/4363069.jpg',	4.4),
(2,	'Rocky II',	130,	'2024-01-03',	'Film',	'Après que le boxeur Rocky Balboa ait tenu la distance avec le champion du monde des poids lourds Apollo Creed, les fans de boxe réclament une revanche.',	'Après que le boxeur Rocky Balboa ait tenu la distance avec le champion du monde des poids lourds Apollo Creed, les fans de boxe réclament une revanche. Mais Rocky, ayant subi de graves blessures, annonce sa retraite. Bien qu\'il tente de refaire sa vie, Rocky réalise qu\'il ne peut échapper à sa véritable vocation. Le ring l\'appelle à nouveau, et \"l\'étalon italien\" doit se préparer pour le combat de sa vie.',	'https://fr.web.img3.acsta.net/c_310_420/pictures/14/08/25/16/12/331381.jpg',	3.3),
(3,	'Shrek',	101,	'2024-01-04',	'Film',	'Shrek, un ogre verdâtre, cynique et malicieux, a élu domicile dans un marécage qu\'il croit être un havre de paix.',	'Shrek, un ogre verdâtre, cynique et malicieux, a élu domicile dans un marécage qu\'il croit être un havre de paix. Un matin, alors qu\'il sort faire sa toilette, il découvre de petites créatures agaçantes qui errent dans son marais. Shrek se rend alors au château du seigneur Lord Farquaad, qui aurait soit-disant expulsé ces êtres de son royaume. Ce dernier souhaite épouser la princesse Fiona, mais celle-ci est retenue prisonnière par un abominable dragon. Il lui faut un chevalier assez brave pour secourir la belle. Shrek accepte d\'accomplir cette mission. En échange, le seigneur devra débarrasser son marécage de ces créatures envahissantes. Or, la princesse Fiona cache un secret terrifiant qui va entraîner Shrek et son compagnon l\'âne dans une palpitante et périlleuse aventure.',	'https://fr.web.img6.acsta.net/c_310_420/medias/nmedia/00/00/00/66/69199338_af.jpg',	2.5),
(4,	'The Wire',	50,	'2024-01-04',	'Série',	'Quand la police s\'efforce de démanteler un réseau tentaculaire de trafic de drogue et du crime à Baltimore.',	'Quand la police s\'efforce de démanteler un réseau tentaculaire de trafic de drogue et du crime à Baltimore.',	'https://fr.web.img3.acsta.net/pictures/23/01/06/14/39/5304402.jpg',	4.9),
(5,	'Stranger Things',	50,	'2024-01-04',	'Série',	'1983, à Hawkins dans l\'Indiana. Après la disparition d\'un garçon de 12 ans dans des circonstances mystérieuses, la petite ville du Midwest est témoin d\'étranges phénomènes.',	'A Hawkins, en 1983 dans l\'Indiana. Lorsque Will Byers disparaît de son domicile, ses amis se lancent dans une recherche semée d’embûches pour le retrouver. Dans leur quête de réponses, les garçons rencontrent une étrange jeune fille en fuite. Les garçons se lient d\'amitié avec la demoiselle tatouée du chiffre \"11\" sur son poignet et au crâne rasé et découvrent petit à petit les détails sur son inquiétante situation. Elle est peut-être la clé de tous les mystères qui se cachent dans cette petite ville en apparence tranquille…',	'https://fr.web.img4.acsta.net/pictures/22/05/18/14/31/5186184.jpg',	4.2);

INSERT INTO `movie_genre` (`movie_id`, `genre_id`) VALUES
(1,	5),
(2,	5),
(3,	3),
(3,	4),
(4,	1),
(4,	2),
(5,	3),
(5,	6);

INSERT INTO `season` (`id`, `movie_id`, `number`, `episodes_number`) VALUES
(1,	5,	1,	10),
(2,	5,	2,	8);

INSERT INTO `person` (`id`, `firstname`, `lastname`) VALUES
(1,	'Sylvester',	'Stallone'),
(2,	'Carl',	'Weathers'),
(3,	'Dominic',	'West'),
(4,	'Idris',	'Elba'),
(5,	'Millie',	'Bobby Brown'),
(6,	'Noah',	'Schnapp'),
(7,	'Eddie',	'Murphy'),
(8,	'Conrad',	'Vernon');

INSERT INTO `casting` (`id`, `person_id`, `movie_id`, `role`, `credit_order`) VALUES
(1,	1,	1,	'Rocky Balboa',	1),
(2,	2,	1,	'Apollo Creed',	2),
(3,	3,	4,	'Det. Jimmy McNulty',	1),
(4,	4,	4,	'Russell \'Stringer\' Bell',	3),
(5,	5,	5,	'Onze',	1),
(6,	6,	5,	'Will Byers',	2),
(7,	7,	3,	'L\'Âne',	1),
(8,	8,	3,	'Ti-Biscuit',	2);