CREATE DATABASE IF NOT EXISTS bantu_consulting;
USE bantu_consulting;

-- Table des utilisateurs admin
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des services
CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100),
    description TEXT,
    icon VARCHAR(50),
    website VARCHAR(255),
    contact_email VARCHAR(100),
    contact_phone VARCHAR(20),
    benefit1_title VARCHAR(100),
    benefit1_desc TEXT,
    benefit2_title VARCHAR(100),
    benefit2_desc TEXT,
    benefit3_title VARCHAR(100),
    benefit3_desc TEXT,
    benefit4_title VARCHAR(100),
    benefit4_desc TEXT,
    process1_title VARCHAR(100),
    process1_desc TEXT,
    process2_title VARCHAR(100),
    process2_desc TEXT,
    process3_title VARCHAR(100),
    process3_desc TEXT,
    process4_title VARCHAR(100),
    process4_desc TEXT,
    fact1 VARCHAR(255),
    fact2 VARCHAR(255),
    fact3 VARCHAR(255),
    fact4 VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des fichiers PDF pour services
CREATE TABLE service_files (
    id INT PRIMARY KEY AUTO_INCREMENT,
    service_id INT,
    file_name VARCHAR(255),
    file_path VARCHAR(255),
    file_type VARCHAR(50),
    file_url VARCHAR(500),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Table des projets
CREATE TABLE projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100),
    description TEXT,
    short_description VARCHAR(255),
    image VARCHAR(255),
    status VARCHAR(50),
    start_date DATE,
    end_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des membres de projet
CREATE TABLE project_members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT,
    member_name VARCHAR(100),
    role VARCHAR(100),
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);

-- Table des pôles/départements
CREATE TABLE departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    description TEXT,
    department_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des équipes
CREATE TABLE teams (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    position VARCHAR(100),
    role VARCHAR(255),
    importance VARCHAR(255),
    image VARCHAR(255),
    department_id INT,
    linkedin VARCHAR(255),
    twitter VARCHAR(255),
    facebook VARCHAR(255),
    instagram VARCHAR(255),
    website VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- Table des contacts/formulaires
CREATE TABLE contacts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table carrousel (à remplacer)
DROP TABLE IF EXISTS carousel;
CREATE TABLE carousel (
    id INT PRIMARY KEY AUTO_INCREMENT,
    image VARCHAR(255),
    title VARCHAR(100),
    description TEXT,
    order_pos INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table devise/à propos
DROP TABLE IF EXISTS about;
CREATE TABLE about (
    id INT PRIMARY KEY AUTO_INCREMENT,
    motto TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
