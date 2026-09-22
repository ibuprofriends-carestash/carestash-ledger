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
('admin', '$2y$10$TIZsd2qtB.J7uaxya0EcP.q1ueyH3NL0PSO1WYq1vfaeBLObkHRqy');

-- Registered application users managed by the admin panel
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('User','Admin','Doctor') NOT NULL DEFAULT 'User',
    status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users (full_name, email, username, password, role, status)
VALUES
('Sample User', 'user@example.com', 'user', '$2y$10$fffibGCBhp3cyAlO0UM3IeuQZWzdgG.HadZi77A.j/dDtIhxyuNYi', 'User', 'Active');

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
