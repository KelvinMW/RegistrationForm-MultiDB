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

-- Insert demo users
INSERT INTO users (username, password) VALUES 
('demo_user1', '$2y$10$DowJonesIndustrialAverageIsTh35e6OHk/tI5NdWz3xPl8a6uELyG9f2To1Tm2'),
('demo_user2', '$2y$10$DowJonesIndustrialAverageIsTh35e6OHk/tI5NdWz3xPl8a6uELyG9f2To1Tm2');

-- Note: The passwords are hashed using bcrypt
