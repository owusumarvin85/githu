-- Create database (safe to run multiple times)
CREATE DATABASE IF NOT EXISTS patient_records CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE patient_records;

-- Users table
CREATE TABLE IF NOT EXISTS users (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(120) NOT NULL,
	email VARCHAR(190) NOT NULL,
	password_hash VARCHAR(255) NOT NULL,
	remember_token_hash CHAR(64) DEFAULT NULL,
	remember_token_expires DATETIME DEFAULT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Patients table
CREATE TABLE IF NOT EXISTS patients (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id INT UNSIGNED NOT NULL,
	name VARCHAR(150) NOT NULL,
	email VARCHAR(190) NULL,
	phone VARCHAR(50) NULL,
	gender ENUM('Male','Female','Other') NULL,
	dob DATE NULL,
	address TEXT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	KEY idx_patients_user_id (user_id),
	UNIQUE KEY uq_patients_user_email (user_id, email),
	CONSTRAINT fk_patients_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

