-- Courier Management System Database
-- Create database and tables

CREATE DATABASE IF NOT EXISTS courier_system;
USE courier_system;

-- Admin table
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Agents table
CREATE TABLE IF NOT EXISTS agents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    city VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Couriers table
CREATE TABLE IF NOT EXISTS couriers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tracking_number VARCHAR(20) UNIQUE NOT NULL,
    sender_name VARCHAR(100) NOT NULL,
    sender_phone VARCHAR(20),
    sender_address TEXT,
    receiver_name VARCHAR(100) NOT NULL,
    receiver_phone VARCHAR(20),
    receiver_address TEXT,
    pickup_city VARCHAR(100) NOT NULL,
    delivery_city VARCHAR(100) NOT NULL,
    courier_type VARCHAR(50) DEFAULT 'Standard',
    weight DECIMAL(10,2),
    delivery_date DATE,
    status ENUM('Pending', 'In Transit', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    created_by INT NOT NULL,
    role ENUM('admin', 'agent') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- SMS logs table
CREATE TABLE IF NOT EXISTS sms_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    courier_id INT,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (courier_id) REFERENCES couriers(id) ON DELETE CASCADE
);

-- Insert demo data
INSERT INTO admins (name, email, password) VALUES 
('Admin User', 'admin@courier.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password

INSERT INTO agents (name, email, password, phone, city) VALUES 
('Agent Smith', 'agent@courier.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567890', 'New York'), -- password
('Agent Johnson', 'agent2@courier.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567891', 'Los Angeles'); -- password

INSERT INTO users (name, email, password, phone) VALUES 
('John User', 'user@courier.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567892'), -- password
('Jane Customer', 'customer@courier.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+1234567893'); -- password

-- Insert sample couriers
INSERT INTO couriers (tracking_number, sender_name, sender_phone, receiver_name, receiver_phone, pickup_city, delivery_city, courier_type, weight, status, created_by, role) VALUES 
('CMS001234', 'John Doe', '+1234567890', 'Alice Smith', '+1987654321', 'New York', 'Los Angeles', 'Express', 2.50, 'In Transit', 1, 'admin'),
('CMS001235', 'Bob Johnson', '+1234567891', 'Carol Brown', '+1987654322', 'Chicago', 'Miami', 'Standard', 1.20, 'Delivered', 1, 'admin'),
('CMS001236', 'David Wilson', '+1234567892', 'Eva Davis', '+1987654323', 'Houston', 'Seattle', 'Express', 3.75, 'Pending', 1, 'admin'),
('CMS001237', 'Frank Miller', '+1234567893', 'Grace Lee', '+1987654324', 'Phoenix', 'Boston', 'Standard', 0.95, 'Cancelled', 1, 'admin');