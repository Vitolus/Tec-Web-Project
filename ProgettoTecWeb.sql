START TRANSACTION;

DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS subscriptions;
DROP TABLE IF EXISTS contacts;

CREATE TABLE `bookings` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `course_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `courses` (
  `id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `max_partecipants` int(10) NOT NULL,
  `user_id` int(10) NOT NULL COMMENT 'Which must refer to a user that''s of type "instructor"'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `courses` (`id`, `title`, `description`, `max_partecipants`, `user_id`) VALUES
(1, 'corsi arrampicata bambini/ragazzi', 'Il corso di arrampicata che proponiamo punta a far scoprire ai ragazzi il magnifico quanto poco conosciuto sport dell’arrampicata sportiva. Le lezioni sono strutturate in maniera tale da fornire le nozioni fondamentali riguardanti la sicurezza (uso corretto dei materiali e delle attrezzature) e le tecniche di arrampicata. Per i più “piccoli”, lo scopo è il divertimento proiettato in verticale, cercando di vincere le proprie paure. Per i più grandi, invece, il fine è quello di diventare autosufficienti prendendo consapevolezza del proprio corpo e del gesto tecnico.', 10, 3),
(2, 'corsi arrampicata adulti', 'L’arrampicata sportiva è il modo più sicuro per progredire su di un terreno verticale, muovendosi in armonia con il proprio corpo. E’ comunque un’attività potenzialmente pericolosa se non praticata con la giusta preparazione!\r\nQuesto corso ha lo scopo di farti apprendere le basi della tecnica di progressione in verticale, ma soprattutto la gestione della sicurezza che ne deriva. Al termine di questo corso sarai in grado di arrampicare insieme ad un compagno sia su terreno artificiale che in quello naturale.', 1, 4);


CREATE TABLE `subscriptions` (
  `id` int(10) NOT NULL,
  `type` varchar(32) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `subscriptions` (`id`, `type`, `start_date`, `end_date`) VALUES
(1, 'Mensile', '2023-01-01', '2023-01-31'),
(2, 'Trimestrale', '2023-01-01', '2023-03-31');


CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(32) NOT NULL,
  `type` varchar(15) NOT NULL,
  `username` varchar(16) NOT NULL,
  `name` varchar(32) NOT NULL,
  `surname` varchar(32) NOT NULL,
  `phone_number` varchar(32) NOT NULL,
  `subscription_id` int(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `email`, `password`, `type`, `username`, `name`, `surname`, `phone_number`, `subscription_id`) VALUES
(1, 'fs12345678@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'admin', 'Francesco', 'Sorge', '321654987', NULL),
(2, 'ei12345678@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'elton', 'Elton', 'Ibra', '123456987', NULL),
(3, 'sm12345678@gmail.com', '175cca0310b93021a7d3cfb3e4877ab6', 'instructor', 'saad', 'Saad', 'Mounib', '123654712', NULL),
(4, 'dv12345678@gmail.com', '175cca0310b93021a7d3cfb3e4877ab6', 'instructor', 'instructor', 'Davide', 'Vitagliano', '987456321', NULL),
(5, 'mr12345678@gmail.com', 'ee11cbb19052e40b07aac0ca060c23ee', 'user', 'user', 'Mario', 'Rossi', '534534534', NULL),
(6, 'db12345678@gmail.com', 'ee11cbb19052e40b07aac0ca060c23ee', 'user', 'daniele', 'Daniele', 'Bianchi', '159738246', NULL);


CREATE TABLE `contacts` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `corse_id` (`course_id`),
  ADD KEY `course_id` (`course_id`);

ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_id` (`subscription_id`);

ALTER TABLE `bookings`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `courses`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `subscriptions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`);

ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `contacts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

COMMIT;