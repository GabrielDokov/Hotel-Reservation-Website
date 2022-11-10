CREATE TABLE `hotels` (
  `id` INT PRIMARY KEY NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `address` VARCHAR(200) NOT NULL,
  `star_rating` INT NOT NULL
);

CREATE TABLE `room_types` (
  `id` INT PRIMARY KEY NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `capacity` INT NOT NULL,
  `price` DOUBLE NOT NULL
);

CREATE TABLE `rooms` (
  `id` INT PRIMARY KEY NOT NULL,
  `room_number` INT NOT NULL,
  `hotel_id` INT NOT NULL,
  `room_type_id` INT NOT NULL
);

CREATE TABLE `roles` (
  `id` INT PRIMARY KEY NOT NULL,
  `name` VARCHAR(100) NOT NULL
);

CREATE TABLE `users` (
  `id` INT PRIMARY KEY NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `role_id` INT NOT NULL
);

CREATE TABLE `packages` (
  `id` INT PRIMARY KEY NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `price` DOUBLE NOT NULL,
  `description` VARCHAR(500) NOT NULL
);

CREATE TABLE `bookings` (
  `id` INT PRIMARY KEY NOT NULL,
  `date_in` DATE NOT NULL,
  `date_out` DATE NOT NULL,
  `number_of_guests` INT NOT NULL,
  `room_id` INT NOT NULL,
  `package_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `total_price` DOUBLE NOT NULL
);

ALTER TABLE `rooms` ADD FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `rooms` ADD FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `users` ADD FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `bookings` ADD FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `bookings` ADD FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `bookings` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
