-- ============================================
-- HOSPITAL DATABASE SCHEMA
-- Université Anténor Firmin (UNAF)
-- Projet: Développement Web Dynamique
-- ============================================

CREATE DATABASE IF NOT EXISTS hospital_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hospital_db;

-- Table: Specialties (Spécialités médicales)
CREATE TABLE specialties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Appointments (Rendez-vous)
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    specialty_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    message TEXT,
    status ENUM('en_attente', 'confirme', 'annule', 'complete') DEFAULT 'en_attente',
    confirmation_date TIMESTAMP NULL,
    admin_response TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (specialty_id) REFERENCES specialties(id) ON DELETE CASCADE
);

-- Table: Contact Messages (Messages de contact)
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('non_lu', 'lu', 'repondu') DEFAULT 'non_lu',
    admin_reply TEXT,
    reply_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: Admin Users (Utilisateurs administrateurs)
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default specialties
INSERT INTO specialties (name, description) VALUES
('Cardiologie', 'Soins du cœur et des vaisseaux sanguins'),
('Dermatologie', 'Soins de la peau'),
('Pédiatrie', 'Soins médicaux pour enfants'),
('Neurologie', 'Soins du système nerveux'),
('Orthopédie', 'Soins des os et des articulations'),
('Ophtalmologie', 'Soins des yeux'),
('Gynécologie', 'Soins de santé féminine'),
('Médecine générale', 'Consultation générale'),
('Dentisterie', 'Soins dentaires'),
('Radiologie', 'Imagerie médicale');

-- Insert default admin user (password: Tague2.0)
INSERT INTO admin_users (username, password, full_name, email) VALUES
('Tague','$2y$12$xwdfcdUZinDvhly9yA5GmepK16DYPmchZ0TfQDpddFi0jtxPU55YK','Administrateur Principal','admin@hospital.com');

-- Create indexes for better search performance
CREATE INDEX idx_appointments_date ON appointments(appointment_date);
CREATE INDEX idx_appointments_status ON appointments(status);
CREATE INDEX idx_contact_status ON contact_messages(status);
CREATE INDEX idx_appointments_name ON appointments(full_name);
