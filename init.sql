CREATE DATABASE IF NOT EXISTS cursos_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cursos_app;

CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    whatsapp_number VARCHAR(20) NOT NULL DEFAULT '5500000000000',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO settings (id, whatsapp_number)
VALUES (1, '5500000000000')
ON DUPLICATE KEY UPDATE whatsapp_number = VALUES(whatsapp_number);

CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    city VARCHAR(100) NOT NULL,
    description TEXT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS course_extra_fields (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    label VARCHAR(120) NOT NULL,
    field_name VARCHAR(120) NOT NULL,
    field_type ENUM('text', 'number', 'date', 'select', 'textarea') NOT NULL DEFAULT 'text',
    options_text TEXT NULL,
    is_required TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    birth_date DATE NOT NULL,
    gender VARCHAR(20) NOT NULL,
    rg VARCHAR(30) NOT NULL,
    cpf VARCHAR(20) NOT NULL,
    whatsapp VARCHAR(25) NOT NULL,
    education VARCHAR(120) NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(120) NOT NULL,
    shirt_size ENUM('P', 'M', 'G', 'GG') NOT NULL,
    photo_path VARCHAR(255) NULL,
    payment_method ENUM('pix', 'boleto', 'cartao', 'dinheiro') NOT NULL,
    accepted_terms TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS registration_extra_values (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_id INT NOT NULL,
    field_id INT NOT NULL,
    value_text TEXT NULL,
    FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE,
    FOREIGN KEY (field_id) REFERENCES course_extra_fields(id) ON DELETE CASCADE
);
