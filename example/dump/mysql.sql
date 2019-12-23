CREATE TABLE `example` (
    `id`        int(11)      NOT NULL,
    `name`      varchar(255) NOT NULL,
    `text`      text         NOT NULL,
    `is_active` int(1)       NOT NULL,
    `status`    varchar(128) NOT NULL,
    `count`     int(11)      NOT NULL,
    `id_type`   int(11)      NOT NULL,
    `cdate`     timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP     ON UPDATE CURRENT_TIMESTAMP,
    `mdate`     timestamp    NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `example`
(`id`, `name`,       `text`,     `is_active`, `status`, `count`, `id_type`, `cdate`,               `mdate`) VALUES
(1,    'foo',        'the text', 1,           'error',   1,      1,         '2019-12-09 11:58:07', '0000-00-00 00:00:00'),
(2,    'bar',        'test 2',   0,           'done',    200,    2,         '2019-12-09 11:59:37', '0000-00-00 00:00:00'),
(4,    'bar (copy)', 'test 3',   0,           'done',    200,    2,         '2019-12-09 11:59:37', '0000-00-00 00:00:00');

CREATE TABLE `example2tags` (
  `id`         int(11) NOT NULL,
  `id_tag`     int(11) NOT NULL,
  `id_example` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `example2tags`
(`id`, `id_tag`, `id_example`) VALUES
(1,    1,        1),
(2,    2,        1);

CREATE TABLE `example_types` (
  `id`   int(11)      NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `example_types`
(`id`, `type`) VALUES
(2,    'bar_type'),
(1,    'foo_type');

CREATE TABLE `tags` (
  `id`    int(11)      NOT NULL,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tags` (`id`, `title`) VALUES
(2, 'bar_tag'),
(1, 'foo_tag');

ALTER TABLE `example`
  ADD PRIMARY KEY            (`id`),
  ADD UNIQUE KEY `name`      (`name`),
  ADD KEY        `is_active` (`is_active`),
  ADD KEY        `status`    (`status`),
  ADD KEY        `id_type`   (`id_type`),
  ADD KEY        `cdate`     (`cdate`)     USING BTREE;

ALTER TABLE `example2tags`
  ADD PRIMARY KEY                    (`id`),
  ADD UNIQUE KEY `id_tag_id_example` (`id_tag`,`id_example`) USING BTREE,
  ADD KEY        `id_tag`            (`id_tag`),
  ADD KEY        `id_example`        (`id_example`);

ALTER TABLE `example_types`
  ADD PRIMARY KEY        (`id`),
  ADD UNIQUE KEY  `type` (`type`);

ALTER TABLE `tags`
  ADD PRIMARY KEY         (`id`),
  ADD UNIQUE KEY  `title` (`title`);

ALTER TABLE `example`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `example2tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `example_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `example`
  ADD CONSTRAINT `example_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `example_types` (`id`);

ALTER TABLE `example2tags`
  ADD CONSTRAINT `example2tags_ibfk_1` FOREIGN KEY (`id_example`) REFERENCES `example` (`id`),
  ADD CONSTRAINT `example2tags_ibfk_2` FOREIGN KEY (`id_tag`)     REFERENCES `tags`    (`id`);
COMMIT;
