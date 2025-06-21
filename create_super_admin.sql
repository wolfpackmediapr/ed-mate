-- Insert super admin user if not exists
INSERT INTO `users` (`username`, `email`, `password`, `role_id`, `first_name`, `last_name`, `is_active`, `created_at`) 
SELECT * FROM (
    SELECT 
        'wolfpackmediapr@gmail.com',
        'wolfpackmediapr@gmail.com',
        '$2y$12$RL/A73hMnn9gsZtDcXIvR.Gq/868ooZSYX7rST63IqbWo/ap5ehPG', -- This will be replaced with actual hashed password
        1, -- role_id 1 = Super Admin
        'Super',
        'Admin',
        1,
        NOW()
) AS tmp
WHERE NOT EXISTS (
    SELECT `user_id` FROM `users` WHERE `email` = 'wolfpackmediapr@gmail.com'
) LIMIT 1; 