CREATE DATABASE IF NOT EXISTS department_db;
USE department_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    department VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    head VARCHAR(100),
    location VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    department VARCHAR(100),
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO departments (name, description, head, location) VALUES
('Computer Science', 'Advancing knowledge in computing, software development, and AI.', 'Dr. Alan Turing', 'Building A, Room 301'),
('Engineering', 'Innovating solutions through mechanical, civil, and electrical engineering.', 'Dr. James Maxwell', 'Building B, Room 205'),
('Business', 'Developing future leaders in management, finance, and marketing.', 'Dr. Sarah Chen', 'Building C, Room 102'),
('Arts', 'Fostering creativity and cultural understanding through diverse programs.', 'Dr. Emily Brooks', 'Building D, Room 401'),
('Science', 'Exploring the natural world through biology, chemistry, and physics.', 'Dr. Richard Feynman', 'Building E, Room 110'),
('Mathematics', 'Building strong foundations in pure and applied mathematics.', 'Dr. Karl Gauss', 'Building A, Room 502');

INSERT INTO announcements (title, content, department, priority, created_by) VALUES
('Fall Semester Registration Open', 'Registration for the Fall 2026 semester is now open. Please visit the registrar office.', NULL, 'high', NULL),
('CS Department Hackathon', 'Annual hackathon event scheduled for October 15-17. All CS students are invited.', 'Computer Science', 'high', NULL),
('Engineering Lab Maintenance', 'The engineering lab will be closed for maintenance from Sep 10-12.', 'Engineering', 'medium', NULL),
('Business Seminar Series', 'Join our weekly seminar series featuring industry leaders every Friday.', 'Business', 'low', NULL),
('Science Fair Submissions', 'Submit your research projects for the annual science fair by October 1.', 'Science', 'medium', NULL);
