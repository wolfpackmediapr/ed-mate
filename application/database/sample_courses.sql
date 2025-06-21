-- Insert sample categories
INSERT INTO `categories` (`category_name`) VALUES
('Programming'),
('Mathematics'),
('Science');

-- Insert a sample user as course creator if not exists
INSERT INTO `users` (`username`, `email`, `password`, `role_id`, `created_at`) 
SELECT * FROM (SELECT 'teacher1', 'teacher1@example.com', 'password123', 2, NOW()) AS tmp
WHERE NOT EXISTS (
    SELECT `user_id` FROM `users` WHERE `email` = 'teacher1@example.com'
) LIMIT 1;

-- Get the user_id of the sample teacher
SET @teacher_id = (SELECT `user_id` FROM `users` WHERE `email` = 'teacher1@example.com' LIMIT 1);

-- Insert sample courses
INSERT INTO `courses` (`title`, `description`, `category_id`, `created_by`, `is_published`) VALUES
('Intro to Python', 'Learn the basics of Python programming.', 1, @teacher_id, 1),
('Algebra I', 'Fundamentals of algebra for beginners.', 2, @teacher_id, 1),
('Physics 101', 'Introduction to basic physics concepts.', 3, @teacher_id, 1);

-- Insert sample course pricing for a student (replace 2 with your student user_id if needed)
INSERT INTO `course_pricing` (`course_id`, `user_id`, `is_paid`, `price`) VALUES
(1, 2, 1, 49.99),
(2, 2, 1, 39.99),
(3, 2, 0, 0.00); 