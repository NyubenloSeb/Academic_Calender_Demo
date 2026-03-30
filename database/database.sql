CREATE DATABASE academic_calendar;

USE academic_calendar;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
);

INSERT INTO admins (username,password)
VALUES ('admin', MD5('admin123'));

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200),
    type VARCHAR(50),
    event_date DATE,
    description TEXT
);