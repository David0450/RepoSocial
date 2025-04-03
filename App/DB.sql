CREATE TABLE `users` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(30) UNIQUE NOT NULL,
  `email` varchar(50) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(30) NOT NULL,
  `last_name` varchar(30),
  `profile_pic` varchar(255)
);

CREATE TABLE `roles` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(15) UNIQUE NOT NULL
);

CREATE TABLE `chats` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `created_at` timestamp NOT NULL
);

CREATE TABLE `projects` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(80) NOT NULL,
  `description` text,
  `category_id` integer NOT NULL,
  `posted_by` integer NOT NULL,
  `created_at` timestamp NOT NULL,
  `status` ENUM ('deleted', 'public', 'private', 'hidden') NOT NULL
);

CREATE TABLE `project_tags` (
  `project_id` integer,
  `tag_id` integer,
  PRIMARY KEY (`project_id`, `tag_id`)
);

CREATE TABLE `comments` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL,
  `posted_by` integer NOT NULL
);

CREATE TABLE `project_comment` (
  `comment_id` integer PRIMARY KEY,
  `project_id` integer NOT NULL
);

CREATE TABLE `comment_subcomment` (
  `subcomment_id` integer PRIMARY KEY,
  `parent_id` integer NOT NULL
);

CREATE TABLE `categories` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(15) UNIQUE NOT NULL
);

CREATE TABLE `tags` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(15) UNIQUE NOT NULL
);

CREATE TABLE `project_members` (
  `user_id` integer,
  `project_id` integer,
  `joined_at` timestamp NOT NULL,
  PRIMARY KEY (`user_id`, `project_id`)
);

CREATE TABLE `chat_members` (
  `chat_id` integer,
  `user_id` integer,
  `joined_at` timestamp NOT NULL,
  PRIMARY KEY (`chat_id`, `user_id`)
);

CREATE TABLE `user_follows` (
  `following_user_id` integer,
  `followed_user_id` integer,
  `follows_since` timestamp NOT NULL,
  PRIMARY KEY (`following_user_id`, `followed_user_id`)
);

CREATE TABLE `user_roles` (
  `user_id` integer,
  `role_id` integer DEFAULT 1,
  PRIMARY KEY (`user_id`, `role_id`)
);

CREATE TABLE `chat_messages` (
  `chat_id` integer NOT NULL,
  `user_id` integer NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`chat_id`, `user_id`)
);

ALTER TABLE `projects` ADD FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

ALTER TABLE `projects` ADD FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `project_tags` ADD FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);

ALTER TABLE `project_tags` ADD FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

ALTER TABLE `project_tags` ADD FOREIGN KEY (`tag_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

ALTER TABLE `comments` ADD FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `project_comment` ADD FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

ALTER TABLE `project_comment` ADD FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

ALTER TABLE `comment_subcomment` ADD FOREIGN KEY (`subcomment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

ALTER TABLE `comment_subcomment` ADD FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE;

ALTER TABLE `project_members` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `project_members` ADD FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

ALTER TABLE `chat_members` ADD FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE;

ALTER TABLE `chat_members` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_follows` ADD FOREIGN KEY (`following_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_follows` ADD FOREIGN KEY (`followed_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_roles` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_roles` ADD FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET DEFAULT;

ALTER TABLE `chat_messages` ADD FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE;

ALTER TABLE `chat_messages` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
