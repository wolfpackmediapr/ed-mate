-- Create roles table
CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default roles
INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Super Admin'),
(2, 'Admin for Teachers'),
(3, 'Mentor'),
(4, 'Student'); 