-- Categories table
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_name` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Courses table
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `category_id` INT,
  `created_by` INT,
  `is_published` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`category_id`),
  FOREIGN KEY (`created_by`) REFERENCES `users`(`user_id`)
);

-- Lessons table
CREATE TABLE IF NOT EXISTS `lessons` (
  `lesson_id` INT AUTO_INCREMENT PRIMARY KEY,
  `lesson_title` VARCHAR(255) NOT NULL,
  `lesson_description` TEXT,
  `course_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `isDeleted` TINYINT(1) DEFAULT 0,
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`course_id`)
);

-- Resources table
CREATE TABLE IF NOT EXISTS `resources` (
  `resource_id` INT AUTO_INCREMENT PRIMARY KEY,
  `lesson_id` INT,
  `resource_title` VARCHAR(255) NOT NULL,
  `resource_type` VARCHAR(50) NOT NULL,
  `resource_url` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `isDeleted` TINYINT(1) DEFAULT 0,
  FOREIGN KEY (`lesson_id`) REFERENCES `lessons`(`lesson_id`)
);

-- Course Pricing table
CREATE TABLE IF NOT EXISTS `course_pricing` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `course_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `is_paid` TINYINT(1) DEFAULT 0,
  `price` DECIMAL(10,2) DEFAULT 0.00,
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`course_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`)
); 