@@ -13,8 +13,8 @@ CREATE TABLE `casting` (
`person_id` int(10) unsigned NOT NULL,
  `role` varchar(100) NOT NULL,
  `credit_order` tinyint(3) unsigned NOT NULL,
  FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`),
  FOREIGN KEY (`person_id`) REFERENCES `person` (`id`)
  FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

TRUNCATE `casting`;
@@ -188,8 +188,8 @@ CREATE TABLE `movie_genre` (
  PRIMARY KEY (`movie_id`,`genre_id`),
  KEY `genre_id` (`genre_id`),
  KEY `movie_id` (`movie_id`),
  CONSTRAINT `movie_genre_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`),
  CONSTRAINT `movie_genre_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`)
  CONSTRAINT `movie_genre_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`) ON DELETE CASCADE,
  CONSTRAINT `movie_genre_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

TRUNCATE `movie_genre`;
@@ -267,8 +267,8 @@ CREATE TABLE `review` (
  `user_id` int(10) unsigned NOT NULL,
  `movie_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
  FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

TRUNCATE `review`;
@@ -342,7 +342,7 @@ CREATE TABLE `season` (
  `movie_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `movie_id` (`movie_id`),
  FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`)
  FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

TRUNCATE `season`;