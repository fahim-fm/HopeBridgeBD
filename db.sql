CREATE DATABASE cf_donation;
USE cf_donation;

-- Users (Donors + Donees + Volunteers + Admin)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('donor','donee','volunteer','admin') DEFAULT 'donor',
    area VARCHAR(100), -- e.g., Cumilla, Feni
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Donations
CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT,
    category ENUM('Cooked Food','Dry Food','Clothes','Winter Items','Stationery/Books') NOT NULL,
    title VARCHAR(150),
    description TEXT,
    image VARCHAR(255),
    status ENUM('Available','Claimed','Delivered') DEFAULT 'Available',
    area VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Volunteers Info (if you want extra details beyond users table)
CREATE TABLE volunteers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    availability ENUM('Active','Not Available') DEFAULT 'Active',
    image VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Requests (for Donees, optional feature)
CREATE TABLE requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donee_id INT,
    category VARCHAR(100),
    description TEXT,
    status ENUM('Pending','Fulfilled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donee_id) REFERENCES users(id) ON DELETE CASCADE
);
