-- Full rebuild schema (drop then create) for Hardware Repair Request Tracker
 create database repair_tracker;
 use repair_tracker;


SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS feedback;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS receipts;
DROP TABLE IF EXISTS repair_updates;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS appointment_proposals;
DROP TABLE IF EXISTS technician_profile;
DROP TABLE IF EXISTS customer_profile;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS requests;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user','technician') NOT NULL DEFAULT 'user',
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  is_disabled TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE customer_profile (
  customer_id INT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(120),
  address TEXT,
  FOREIGN KEY (customer_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE technician_profile (
  technician_id INT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(120),
  specialization VARCHAR(120),
  experience_years INT DEFAULT 0,
  availability_notes VARCHAR(255),
  FOREIGN KEY (technician_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE requests (
  request_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  device_type VARCHAR(100) NOT NULL,
  model VARCHAR(100) DEFAULT NULL,
  serial_no VARCHAR(100) DEFAULT NULL,
  category ENUM('Hardware','Software','Other') NOT NULL,
  description TEXT NOT NULL,
  state ENUM('New','Assigned','Scheduled','Device Received','In Progress','Completed','Returned','Cannot Fix','On Hold','No-Show','Rejected') NOT NULL DEFAULT 'New',
  assigned_to INT NULL,
  status ENUM('Pending','Approved','In Progress','Completed','Rejected') NOT NULL DEFAULT 'Pending',
  admin_status ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
  technician_id INT NULL,
  tech_assigned INT NULL,
  appointment_time DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  FOREIGN KEY (technician_id) REFERENCES users(user_id) ON DELETE SET NULL,
  FOREIGN KEY (tech_assigned) REFERENCES users(user_id) ON DELETE SET NULL,
  FOREIGN KEY (assigned_to) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE comments (
  comment_id INT AUTO_INCREMENT PRIMARY KEY,
  request_id INT NOT NULL,
  user_id INT NOT NULL,
  comment_text TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (request_id) REFERENCES requests(request_id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE appointment_proposals (
  proposal_id INT AUTO_INCREMENT PRIMARY KEY,
  request_id INT NOT NULL,
  technician_id INT NOT NULL,
  slot1 DATETIME NOT NULL,
  slot2 DATETIME NULL,
  slot3 DATETIME NULL,
  status ENUM('Waiting','Accepted','Rejected') DEFAULT 'Waiting',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (request_id) REFERENCES requests(request_id) ON DELETE CASCADE,
  FOREIGN KEY (technician_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE appointments (
  appointment_id INT AUTO_INCREMENT PRIMARY KEY,
  request_id INT NOT NULL,
  technician_id INT NOT NULL,
  chosen_slot DATETIME NOT NULL,
  device_received TINYINT(1) DEFAULT 0,
  no_show TINYINT(1) DEFAULT 0,
  returned_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (request_id) REFERENCES requests(request_id) ON DELETE CASCADE,
  FOREIGN KEY (technician_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE repair_updates (
  update_id INT AUTO_INCREMENT PRIMARY KEY,
  request_id INT NOT NULL,
  technician_id INT NOT NULL,
  status ENUM('Pending','In Progress','Completed','Cannot Fix','On Hold') NOT NULL,
  note VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (request_id) REFERENCES requests(request_id) ON DELETE CASCADE,
  FOREIGN KEY (technician_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE receipts (
  receipt_id INT AUTO_INCREMENT PRIMARY KEY,
  request_id INT NOT NULL,
  technician_id INT NOT NULL,
  items TEXT,
  total_amount DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (request_id) REFERENCES requests(request_id) ON DELETE CASCADE,
  FOREIGN KEY (technician_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE payments (
  payment_id INT AUTO_INCREMENT PRIMARY KEY,
  receipt_id INT NOT NULL,
  method ENUM('Online','Cash') DEFAULT 'Cash',
  status ENUM('Pending','Paid') DEFAULT 'Pending',
  paid_at DATETIME NULL,
  FOREIGN KEY (receipt_id) REFERENCES receipts(receipt_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE feedback (
  feedback_id INT AUTO_INCREMENT PRIMARY KEY,
  request_id INT NOT NULL,
  from_user INT NOT NULL,
  to_user INT NOT NULL,
  role_view ENUM('customer_to_technician','technician_to_customer') NOT NULL,
  rating TINYINT NOT NULL,
  comment TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (request_id) REFERENCES requests(request_id) ON DELETE CASCADE,
  FOREIGN KEY (from_user) REFERENCES users(user_id),
  FOREIGN KEY (to_user) REFERENCES users(user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data
INSERT INTO users(username,password,role,status,is_disabled) VALUES
 ('admin','admin','admin','approved',0);

-- No sample customer/technician or requests seeded (clean start with only admin)

