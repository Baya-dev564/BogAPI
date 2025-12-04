<<<<<<< HEAD
-- =======================================
-- BLOGAPI DATABASE - CRÉATION COMPLÈTE
-- Développé par Baya AMELLAL PAYAN
-- =======================================

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS blogapi_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE blogapi_db;

-- =======================================
-- TABLE USERS (Utilisateurs)
-- =======================================
=======
-- BlogAPI - Base de données
-- Développé par Baya AMELLAL PAYAN
-- Activité-Type 2 : Développer la partie back-end

USE blogapi_db;

-- ============================================
-- TABLE: users (Utilisateurs)
-- ============================================
>>>>>>> 1fd7e698b777bcde9daeb634e7dc5bc1f33cbcfd
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('admin', 'author', 'subscriber') DEFAULT 'subscriber',
<<<<<<< HEAD
    avatar VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Index sur les champs fréquemment utilisés
    INDEX idx_role (role),
    INDEX idx_is_active (is_active)
);

-- =======================================
-- TABLE CATEGORIES (Catégories)
-- =======================================
=======
    avatar VARCHAR(255) NULL,
    bio TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_users_email (email),
    INDEX idx_users_username (username),
    INDEX idx_users_role (role)
);

-- ============================================
-- TABLE: categories (Catégories d'articles)
-- ============================================
>>>>>>> 1fd7e698b777bcde9daeb634e7dc5bc1f33cbcfd
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
<<<<<<< HEAD
    description TEXT DEFAULT NULL,
    color VARCHAR(7) DEFAULT '#007cba',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Index pour les recherches
    INDEX idx_is_active (is_active)
);

-- =======================================
-- TABLE ARTICLES (Articles de blog)
-- =======================================
=======
    description TEXT NULL,
    color VARCHAR(7) DEFAULT '#007cba',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_categories_slug (slug),
    INDEX idx_categories_active (is_active)
);

-- ============================================
-- TABLE: articles (Articles de blog)
-- ============================================
>>>>>>> 1fd7e698b777bcde9daeb634e7dc5bc1f33cbcfd
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
<<<<<<< HEAD
    excerpt TEXT DEFAULT NULL,
    content LONGTEXT NOT NULL,
    featured_image VARCHAR(255) DEFAULT NULL,
    author_id INT NOT NULL,
    category_id INT NOT NULL,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    is_featured TINYINT(1) DEFAULT 0,
    view_count INT DEFAULT 0,
    published_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Clés étrangères
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    
    -- Index pour optimiser les performances
    INDEX idx_author (author_id),
    INDEX idx_category (category_id),
    INDEX idx_status (status),
    INDEX idx_featured (is_featured),
    INDEX idx_published_at (published_at)
);

-- =======================================
-- TABLE COMMENTS (Commentaires)
-- =======================================
=======
    excerpt TEXT NULL,
    content LONGTEXT NOT NULL,
    featured_image VARCHAR(255) NULL,
    author_id INT NOT NULL,
    category_id INT NOT NULL,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    is_featured BOOLEAN DEFAULT FALSE,
    view_count INT DEFAULT 0,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    
    INDEX idx_articles_slug (slug),
    INDEX idx_articles_author (author_id),
    INDEX idx_articles_category (category_id),
    INDEX idx_articles_status (status),
    INDEX idx_articles_published (published_at),
    INDEX idx_articles_featured (is_featured)
);

-- ============================================
-- TABLE: comments (Commentaires)
-- ============================================
>>>>>>> 1fd7e698b777bcde9daeb634e7dc5bc1f33cbcfd
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    user_id INT NOT NULL,
<<<<<<< HEAD
    parent_id INT DEFAULT NULL,
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    is_pinned TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Clés étrangères
=======
    parent_id INT NULL,
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    is_pinned BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
>>>>>>> 1fd7e698b777bcde9daeb634e7dc5bc1f33cbcfd
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE,
    
<<<<<<< HEAD
    -- Index pour les performances
    INDEX idx_article (article_id),
    INDEX idx_user (user_id),
    INDEX idx_parent (parent_id),
    INDEX idx_status (status)
);

-- =======================================
-- DONNÉES DE TEST (Optionnel)
-- =======================================

-- Insertion d'un utilisateur admin
INSERT INTO users (username, email, password, first_name, last_name, role, is_active) 
VALUES ('admin', 'admin@blogapi.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'BlogAPI', 'admin', 1);

-- Insertion de catégories par défaut
INSERT INTO categories (name, slug, description, color, is_active) VALUES
('Technologie', 'technologie', 'Articles sur les nouvelles technologies', '#007cba', 1),
('Développement', 'developpement', 'Tutoriels et guides de développement', '#28a745', 1),
('Design', 'design', 'Articles sur le design et l\'UX/UI', '#dc3545', 1),
('Actualités', 'actualites', 'Dernières actualités du secteur', '#ffc107', 1);

-- =======================================
-- VUES UTILES (Optionnel)
-- =======================================

-- Vue pour les articles publiés avec informations auteur et catégorie
CREATE VIEW published_articles AS
SELECT 
    a.id,
    a.title,
    a.slug,
    a.excerpt,
    a.content,
    a.featured_image,
    a.view_count,
    a.is_featured,
    a.published_at,
    a.created_at,
    CONCAT(u.first_name, ' ', u.last_name) AS author_name,
    u.username AS author_username,
    c.name AS category_name,
    c.slug AS category_slug,
    c.color AS category_color
FROM articles a
INNER JOIN users u ON a.author_id = u.id
INNER JOIN categories c ON a.category_id = c.id
WHERE a.status = 'published' AND c.is_active = 1 AND u.is_active = 1
ORDER BY a.published_at DESC;

-- =======================================
-- INDEX SUPPLÉMENTAIRES POUR PERFORMANCES
-- =======================================

-- Index composé pour recherche d'articles
CREATE INDEX idx_articles_search ON articles(status, published_at DESC);

-- Index pour les statistiques
CREATE INDEX idx_articles_stats ON articles(author_id, status, created_at);

-- =======================================
-- CONTRAINTES DE VALIDATION
-- =======================================

-- Vérification que published_at n'est pas null pour les articles publiés
DELIMITER //
CREATE TRIGGER articles_published_at_check 
BEFORE UPDATE ON articles
FOR EACH ROW
BEGIN
    IF NEW.status = 'published' AND NEW.published_at IS NULL THEN
        SET NEW.published_at = NOW();
    END IF;
END//
DELIMITER ;

-- =======================================
-- PERMISSIONS ET UTILISATEURS
-- =======================================

-- Création utilisateur pour l'application (à adapter)
-- CREATE USER 'blogapi_user'@'localhost' IDENTIFIED BY 'blogapi_password';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON blogapi_db.* TO 'blogapi_user'@'localhost';
-- FLUSH PRIVILEGES;
=======
    INDEX idx_comments_article (article_id),
    INDEX idx_comments_user (user_id),
    INDEX idx_comments_parent (parent_id),
    INDEX idx_comments_status (status)
);
>>>>>>> 1fd7e698b777bcde9daeb634e7dc5bc1f33cbcfd
