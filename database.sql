CREATE DATABASE IF NOT EXISTS lost_found_system;
USE lost_found_system;

CREATE TABLE User (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(15)
);

CREATE TABLE Category (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(50) NOT NULL
);

CREATE TABLE Item (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    description TEXT,
    date_reported DATE,
    location VARCHAR(100),
    status VARCHAR(20),
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES Category(category_id)
);

CREATE TABLE Report (
    report_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    item_id INT,
    report_type VARCHAR(20),
    report_date DATE,
    FOREIGN KEY (user_id) REFERENCES User(user_id),
    FOREIGN KEY (item_id) REFERENCES Item(item_id)
);

CREATE TABLE Claim (
    claim_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    item_id INT,
    claim_date DATE,
    status VARCHAR(20) DEFAULT 'Pending',
    verified_by INT,
    FOREIGN KEY (user_id) REFERENCES User(user_id),
    FOREIGN KEY (item_id) REFERENCES Item(item_id),
    FOREIGN KEY (verified_by) REFERENCES User(user_id)
);