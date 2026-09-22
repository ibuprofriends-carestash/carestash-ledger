CREATE DATABASE IF NOT EXISTS health_records_db;
USE health_records_db;

-- Admin users who can log into the admin panel
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (username, password) VALUES
('admin', '$2b$12$CVwqYStGFLBKouBfTz.m1.mWEhhjNWs8.fctfZfsYmPx..jmiJATq');

-- Patient health records
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male','Female','Other') NOT NULL,
    contact_number VARCHAR(20),
    address VARCHAR(255),
    blood_type VARCHAR(5),
    diagnosis TEXT,
    allergies TEXT,
    medications TEXT,
    doctor_name VARCHAR(100),
    admission_date DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
