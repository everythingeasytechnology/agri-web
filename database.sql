CREATE DATABASE IF NOT EXISTS agri_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agri_web;

-- If upgrading an existing database, run this to add the slug column:
-- ALTER TABLE blogs ADD COLUMN slug VARCHAR(300) NULL UNIQUE AFTER title;

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(50) NOT NULL DEFAULT 'admin',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO admins (username, password_hash, role) VALUES
('admin', '$2y$10$OLSz/qeWccrVF3MMfAVq9.igl7ada/BTOFwmmFZZgFc46IbsnFwUu', 'superadmin');

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  category VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  image_url VARCHAR(500),
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS blogs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(300) NULL UNIQUE,
  author VARCHAR(150) NOT NULL,
  category VARCHAR(150) NOT NULL,
  excerpt TEXT NOT NULL,
  content LONGTEXT NOT NULL,
  image_url VARCHAR(500),
  status ENUM('published','draft') NOT NULL DEFAULT 'published',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contact_queries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL,
  phone VARCHAR(100),
  subject VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  status ENUM('new','read','replied') NOT NULL DEFAULT 'new',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO products (name, category, description, image_url) VALUES
('Chia Seeds', 'Seeds & Grains', 'Premium chia seeds for healthy food makers and export buyers.', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=700&h=400&fit=crop&q=80'),
('Turmeric Powder', 'Spices', 'High quality turmeric powder sourced from trusted farms.', 'https://images.unsplash.com/photo-1615485500704-8e990f9900f7?w=700&h=400&fit=crop&q=80');

CREATE TABLE IF NOT EXISTS instagram_reels (
  id INT AUTO_INCREMENT PRIMARY KEY,
  label VARCHAR(150) NOT NULL,
  reel_url VARCHAR(500) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Run this if adding to existing database:
-- CREATE TABLE IF NOT EXISTS instagram_reels (id INT AUTO_INCREMENT PRIMARY KEY, label VARCHAR(150) NOT NULL, reel_url VARCHAR(500) NOT NULL, sort_order INT NOT NULL DEFAULT 0, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO blogs (title, slug, author, category, excerpt, content, image_url, status) VALUES
('Global Demand for Chia Seeds', 'global-demand-for-chia-seeds', 'Ficus International Team', 'Agro Commodities', 'Understand why chia seeds are in rising demand across Europe and Asia.', '<p>Chia seeds are now popular for health, nutrition and plant-based diets.</p>', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=700&h=350&fit=crop&q=80', 'published'),
('Sourcing Spices Directly from Origin', 'sourcing-spices-directly-from-origin', 'Ficus International Team', 'Trade & Export', 'Direct sourcing improves quality, traceability and price stability.', '<p>Origin sourcing means better relationships with farmers and cleaner supply chains.</p>', 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=700&h=350&fit=crop&q=80', 'published');
