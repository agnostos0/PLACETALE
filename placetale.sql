-- PlaceTale schema and seed
CREATE DATABASE IF NOT EXISTS placetale_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE placetale_db;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS events (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  event_date DATE NOT NULL,
  place VARCHAR(120) NOT NULL,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO events (title, event_date, place, description) VALUES
('Storytelling Meetup #1','2025-10-01','Campus Hall','A collaborative session to share and refine place-based stories.'),
('Storytelling Meetup #2','2025-10-05','Library Lawn','A collaborative session to share and refine place-based stories.'),
('Storytelling Meetup #3','2025-10-09','Auditorium','A collaborative session to share and refine place-based stories.'),
('Storytelling Meetup #4','2025-10-12','Online','A collaborative session to share and refine place-based stories.'),
('Storytelling Meetup #5','2025-10-15','Campus Hall','A collaborative session to share and refine place-based stories.'),
('Storytelling Meetup #6','2025-10-20','Library Lawn','A collaborative session to share and refine place-based stories.');


