CREATE TABLE `url` (
  `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `url` varchar(255) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE='InnoDB' COLLATE 'utf8_general_ci';


INSERT INTO `url` (`url`)
VALUES ('http://verdure.net');

INSERT INTO `url` (`url`)
VALUES ('https://github.com/Lexxtor/url_shortener/blob/master/migrations/m160626_121505_init.php');