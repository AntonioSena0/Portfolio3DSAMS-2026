CREATE DATABASE ams_laravel_db;
USE ams_laravel_db;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
) ENGINE = InnoDB;

CREATE TABLE profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    biography VARCHAR(1000) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    CONSTRAINT profiles_user_id_foreign
        FOREIGN KEY (user_id) REFERENCES users (id)
        ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE posts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    INDEX posts_user_id_index (user_id),
    CONSTRAINT posts_user_id_foreign
        FOREIGN KEY (user_id) REFERENCES users (id)
        ON DELETE CASCADE
) ENGINE = InnoDB;

CREATE TABLE tags (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE
) ENGINE = InnoDB;

CREATE TABLE post_tag (
    post_id BIGINT UNSIGNED NOT NULL,
    tag_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    INDEX post_tag_tag_id_index (tag_id),
    CONSTRAINT post_tag_post_id_foreign
        FOREIGN KEY (post_id) REFERENCES posts (id)
        ON DELETE CASCADE,
    CONSTRAINT post_tag_tag_id_foreign
        FOREIGN KEY (tag_id) REFERENCES tags (id)
        ON DELETE CASCADE
) ENGINE = InnoDB;
