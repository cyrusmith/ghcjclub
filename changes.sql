ALTER TABLE cjclub_articles ADD COLUMN authorId int(10) NOT NULL DEFAULT '0' AFTER tags_cached;

--
-- Описание для таблицы cjclub_playlists
--
DROP TABLE IF EXISTS cjclub_playlists;
CREATE TABLE cjclub_playlists (
  id INT(11) NOT NULL AUTO_INCREMENT,
  userId INT(11) NOT NULL,
  name VARCHAR(255) NOT NULL,
  public ENUM('yes','no') NOT NULL,
  PRIMARY KEY (id),
  INDEX IDX_cjclub_playlists_userId (userId)
)
ENGINE = INNODB
AUTO_INCREMENT = 27
AVG_ROW_LENGTH = 630
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы cjclub_playlists_have_tracks
--
DROP TABLE IF EXISTS cjclub_playlists_have_tracks;
CREATE TABLE cjclub_playlists_have_tracks (
  trackId INT(11) NOT NULL,
  listId INT(11) NOT NULL,
  `order` INT(11) NOT NULL,
  PRIMARY KEY (trackId, listId)
)
ENGINE = INNODB
AVG_ROW_LENGTH = 1024
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Вывод данных для таблицы cjclub_playlists
--
INSERT INTO cjclub_playlists VALUES
(1, 1, 'my first', 'yes'),
(2, 1, 'my second', 'yes'),
(3, 1, 'my third', 'yes'),
(4, 1, 'private 1', 'no'),
(5, 1, 'private 2', 'no'),
(6, 1, 'private 3', 'no'),
(7, 4, 'random', 'yes'),
(8, 5, 'user', 'yes'),
(9, 6, 'playlists', 'no'),
(10, 7, 'must', 'no'),
(11, 8, 'die', 'yes'),
(12, 9, 'coz', 'yes'),
(13, 9, 'there is', 'yes'),
(14, 9, 'a lot', 'yes'),
(15, 9, 'of stupid', 'no'),
(16, 9, 'work', 'yes'),
(17, 10, 'with', 'yes'),
(18, 10, 'same', 'yes'),
(19, 11, 'same', 'no'),
(20, 11, 'data', 'yes'),
(21, 11, 'data', 'yes'),
(22, 12, 'ok', 'yes'),
(23, 12, 'tryhard ', 'no'),
(24, 13, 'is', 'yes'),
(25, 14, 'over', 'yes'),
(26, 14, 'now', 'yes');

--
-- Вывод данных для таблицы cjclub_playlists_have_tracks
--
INSERT INTO cjclub_playlists_have_tracks VALUES
(1, 1, 1),
(4, 1, 2),
(6, 1, 3),
(10, 1, 4),
(15, 1, 5),
(17, 1, 6),
(20, 1, 7),
(101, 2, 1),
(103, 2, 3),
(104, 2, 4),
(105, 2, 5),
(106, 2, 6),
(107, 2, 7),
(108, 2, 9),
(300, 3, 1),
(302, 3, 2);
