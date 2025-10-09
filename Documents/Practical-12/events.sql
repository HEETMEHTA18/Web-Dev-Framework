CREATE DATABASE eventdb;
USE eventdb;

CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    location VARCHAR(100) NOT NULL,
    status ENUM('open','closed') DEFAULT 'open',
    poster VARCHAR(255) DEFAULT NULL
);

INSERT INTO events (title, date, location, status) VALUES
('Tech Fest', '2025-08-10', 'Seminar Hall', 'open'),
('Hackathon', '2025-09-12', 'Lab Block', 'closed'),
('Coding Marathon', '2025-07-22', 'Online', 'open'),
('Quiz Competition', '2025-07-30', 'Auditorium', 'open'),
('Seminar AI', '2025-08-05', 'Room 202', 'closed');
