-- Create a database
CREATE DATABASE user_auth_db;

-- Use the database
USE user_auth_db;

-- Create a users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Note: The passwords are hashed using bcrypt
-- Add active column to the users table
ALTER TABLE users ADD active TINYINT(1) NOT NULL DEFAULT 0;

