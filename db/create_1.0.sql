CREATE TABLE `genre` (
 `id` bigint(20) unsigned NOT NULL,
 `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ID3 genres/IDs used by itunes http://eyed3.nicfit.net/plugins/genres_plugin.html';

CREATE TABLE `song` (
 `id` bigint(20) NOT NULL,
 `rank` tinyint(4) DEFAULT NULL,
 `id_genre` bigint(20) unsigned NOT NULL,
 PRIMARY KEY (`id`,`id_genre`),
 KEY `fk_genre` (`id_genre`),
 CONSTRAINT `fk_genre` FOREIGN KEY (`id_genre`) REFERENCES `genre` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `page` (
 `id` bigint(20) NOT NULL,
 `id_genre` bigint(20) unsigned NOT NULL,
 `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NULL',
 `access_token` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'NULL',
 PRIMARY KEY (`id`),
 KEY `access_token` (`access_token`(255)),
 KEY `id_genre` (`id_genre`),
 CONSTRAINT `page_ibfk_1` FOREIGN KEY (`id_genre`) REFERENCES `genre` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;