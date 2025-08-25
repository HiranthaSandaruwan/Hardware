-- Schema and seed data for Hardware Repair Request Tracker
DROP TABLE IF EXISTS comments;DROP TABLE IF EXISTS requests;DROP TABLE IF EXISTS users;
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user','technician') NOT NULL DEFAULT 'user',
  status ENUM('pending','approved') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE requests (
  request_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  device_type VARCHAR(100) NOT NULL,
  model VARCHAR(100) DEFAULT NULL,
  serial_no VARCHAR(100) DEFAULT NULL,
  category ENUM('Hardware','Software','Other') NOT NULL,
  description TEXT NOT NULL,
  status ENUM('Pending','Approved','In Progress','Completed','Rejected') NOT NULL DEFAULT 'Pending',
  technician_id INT NULL,
  appointment_time DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_req_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_req_technician FOREIGN KEY (technician_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE comments (
  comment_id INT AUTO_INCREMENT PRIMARY KEY,
  request_id INT NOT NULL,
  user_id INT NOT NULL,
  comment_text TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_com_req FOREIGN KEY (request_id) REFERENCES requests(request_id) ON DELETE CASCADE,
  CONSTRAINT fk_com_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed admin and sample users (plain passwords per project spec)
INSERT INTO users(username,password,role,status) VALUES
 ('admin','admin','admin','approved'),
 ('uoc','uoc','user','approved'),
 ('tech1','tech1','technician','approved');

-- Sample requests
INSERT INTO requests(user_id,device_type,model,serial_no,category,description,status,created_at,updated_at) VALUES
 (2,'Laptop','Dell Latitude','SN123','Hardware','Won\'t power on','Pending',NOW(),NOW()),
 (2,'Desktop','HP ProDesk','SN456','Software','Blue screen error','Approved',NOW(),NOW());
