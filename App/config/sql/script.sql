-- Supprimer la base de données si elle existe
DROP DATABASE IF EXISTS Youdemy_db;

-- Créer la base de données
CREATE DATABASE youdemy_db;
USE youdemy_db;

-- Table des utilisateurs
CREATE TABLE users (
    user_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(250) NOT NULL,
    email VARCHAR(250) NOT NULL UNIQUE,
    password VARCHAR(250) NOT NULL,
    bio VARCHAR(250),
    role ENUM('etudiant', 'enseignant', 'admin') NOT NULL,
    status ENUM('active', 'suspended') DEFAULT 'active',
    PRIMARY KEY (user_id)
);

-- Table des catégories
CREATE TABLE categories (
    category_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(250) NOT NULL,
    PRIMARY KEY (category_id)
);

-- Table des tags
CREATE TABLE tags (
    tag_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(250) NOT NULL,
    PRIMARY KEY (tag_id)
);

-- Table des cours-- Table des cours
CREATE TABLE courses (
    course_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    titre VARCHAR(250) NOT NULL,
    description TEXT NOT NULL,
    contenu ENUM('video', 'document') NOT NULL,
    status ENUM('soumis', 'accepte', 'refuse') DEFAULT 'soumis',
    date_status TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    category_id BIGINT UNSIGNED,
    enseignant_id BIGINT UNSIGNED NOT NULL,
    video_url VARCHAR(255) DEFAULT NULL, 
    document_text TEXT DEFAULT NULL, 
    PRIMARY KEY (course_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (enseignant_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
);


-- Table des relations entre cours et tags
CREATE TABLE course_tag (
    course_id BIGINT UNSIGNED NOT NULL,
    tag_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (course_id, tag_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(tag_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table des inscriptions
CREATE TABLE inscriptions (
    inscription_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id BIGINT UNSIGNED NOT NULL,
    course_id BIGINT UNSIGNED NOT NULL,
    date_enrolled TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'cancelled') DEFAULT 'active',
    PRIMARY KEY (inscription_id),
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE
);

-- Indexation pour optimiser les recherches
CREATE INDEX idx_category_id ON courses(category_id);
CREATE INDEX idx_enseignant_id ON courses(enseignant_id);
CREATE INDEX idx_student_id ON inscriptions(student_id);
CREATE INDEX idx_course_id ON inscriptions(course_id);
