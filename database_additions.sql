-- PawHome: extra tables for Admin + User features
-- Run AFTER importing your original "database" file.
USE pet_adoption;

DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS admins;

-- ADMIN ACCOUNTS (password is stored hashed, never plain text)
CREATE TABLE admins (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (username, password_hash) VALUES
('admin', 'admin123');

-- USER COMMENTS / REVIEWS ON A PET
CREATE TABLE reviews (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    pet_id      INT          NOT NULL,
    name        VARCHAR(60)  NOT NULL,
    rating      TINYINT      NOT NULL,
    comment     TEXT         NOT NULL,
    created_at  TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE
);

INSERT INTO reviews (pet_id, name, rating, comment) VALUES
(1, 'Aisyah', 5, 'Oreo is so sweet! Lovely cat.'),
(3, 'Hafiz',  4, 'Max is very playful and friendly.');

-- Create your admin login by opening setup_admin.php once in the browser,
-- then DELETE setup_admin.php.
