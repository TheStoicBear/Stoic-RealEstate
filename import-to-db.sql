CREATE TABLE IF NOT EXISTS `agents` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `profile_picture` VARCHAR(255) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `discord_id` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `garages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `property_id` INT(11) NOT NULL,
  `capacity` INT(11) NOT NULL DEFAULT 1,
  `location` VARCHAR(255) NOT NULL,
  `latitude` DECIMAL(10,6) NOT NULL,
  `longitude` DECIMAL(10,6) NOT NULL,
  `altitude` DECIMAL(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `garages_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `garage_vehicles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `garage_id` INT(11) NOT NULL,
  `model` VARCHAR(255) NOT NULL,
  `plate` VARCHAR(20) NOT NULL,
  `color1` INT(11) DEFAULT NULL,
  `color2` INT(11) DEFAULT NULL,
  `x` DECIMAL(10,6) NOT NULL,
  `y` DECIMAL(10,6) NOT NULL,
  `z` DECIMAL(10,6) NOT NULL,
  `h` DECIMAL(10,2) NOT NULL,
  `parked` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `garage_id` (`garage_id`),
  CONSTRAINT `garage_vehicles_ibfk_1` FOREIGN KEY (`garage_id`) REFERENCES `garages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `rentals` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `property_id` INT(11) NOT NULL,
  `tenant_id` INT(11) NOT NULL,
  `start_date` DATE NOT NULL,
  `rent_amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending','paid','overdue') DEFAULT 'pending',
  `due_date` DATE DEFAULT NULL,
  `payment_status` ENUM('pending','paid','overdue') DEFAULT 'pending',
  `agent_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  KEY `tenant_id` (`tenant_id`),
  CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`),
  CONSTRAINT `rentals_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tenants` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `discord_id` VARCHAR(20) DEFAULT NULL,
  `user_id` INT(11) NOT NULL,
  `profile_picture` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(15) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `agent` TINYINT(1) NOT NULL DEFAULT 0,
  `role` ENUM('agent','tenant') NOT NULL DEFAULT 'tenant',
  `name` VARCHAR(255) NOT NULL,
  `profile_picture` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;
