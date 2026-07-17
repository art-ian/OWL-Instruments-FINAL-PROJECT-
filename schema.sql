-- creating database
CREATE DATABASE IF NOT EXISTS inventory_system;

-- open the database
USE inventory_system;

-- create admins table 
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    role VARCHAR(50) NOT NULL
);

-- for the products table 
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    status VARCHAR(20) DEFAULT 'active'
);

-- add default admin user
INSERT INTO admins (username, role) 
VALUES ('superadmin', 'Super Admin');