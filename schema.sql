CREATE DATABASE IF NOT EXISTS gym_management;
USE gym_management;

CREATE TABLE members (
  member_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  age INT NOT NULL,
  gender ENUM('Male','Female','Other') NOT NULL,
  mobile VARCHAR(10) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  address TEXT NOT NULL,
  join_date DATE NOT NULL,
  profile_photo VARCHAR(255) DEFAULT NULL,
  status ENUM('Active','Expired','Expiring Soon') DEFAULT 'Active'
);

CREATE TABLE plans (
  plan_id INT AUTO_INCREMENT PRIMARY KEY,
  plan_name VARCHAR(100) NOT NULL,
  duration_months INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  description TEXT
);

CREATE TABLE memberships (
  membership_id INT AUTO_INCREMENT PRIMARY KEY,
  member_id INT NOT NULL,
  plan_id INT NOT NULL,
  start_date DATE NOT NULL,
  expiry_date DATE NOT NULL,
  FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE,
  FOREIGN KEY (plan_id) REFERENCES plans(plan_id)
);

CREATE TABLE payments (
  payment_id INT AUTO_INCREMENT PRIMARY KEY,
  member_id INT NOT NULL,
  membership_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  payment_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status ENUM('Paid','Pending','Partial') DEFAULT 'Paid',
  notes TEXT,
  FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE,
  FOREIGN KEY (membership_id) REFERENCES memberships(membership_id) ON DELETE CASCADE
);

CREATE TABLE admin_users (
  admin_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Seed Data (Admin)
-- password: Admin@123
INSERT INTO admin_users (username, email, password_hash) VALUES 
('admin', 'admin@gym.com', '$2y$10$U9z7d7gE9t.k50880lO7HOUC0gNInL0qM.7d0S4f9/JIFi9oU.dMW');

-- Seed Data (Plans)
INSERT INTO plans (plan_name, duration_months, price, description) VALUES
('Basic', 1, 800.00, 'Access to basic gym equipment'),
('Standard', 3, 2100.00, 'Full equipment + 1 personal training session'),
('Premium', 12, 7200.00, 'Full access + unlimited personal training + locker');

-- Seed Data (Members)
INSERT INTO members (name, age, gender, mobile, email, address, join_date) VALUES 
('Rohan Patil', 23, 'Male', '9823456781', 'rohan@gmail.com', 'Nashik', DATE_SUB(CURDATE(), INTERVAL 6 MONTH)),
('Priya Deshmukh', 28, 'Female', '9765432108', 'priya@gmail.com', 'Pune', DATE_SUB(CURDATE(), INTERVAL 1 MONTH)),
('Amit Kulkarni', 35, 'Male', '9887654321', 'amit@gmail.com', 'Mumbai', CURDATE()),
('Sneha Joshi', 19, 'Female', '9654321078', 'sneha@gmail.com', 'Nashik', DATE_SUB(CURDATE(), INTERVAL 362 DAY)),
('Vikram Naik', 31, 'Male', '9543210987', 'vikram@gmail.com', 'Aurangabad', DATE_SUB(CURDATE(), INTERVAL 2 MONTH));

-- Seed Data (Memberships)
-- Rohan: Premium (12 mo)
INSERT INTO memberships (member_id, plan_id, start_date, expiry_date) VALUES 
(1, 3, DATE_SUB(CURDATE(), INTERVAL 6 MONTH), DATE_ADD(CURDATE(), INTERVAL 6 MONTH));
-- Priya: Standard (3 mo)
INSERT INTO memberships (member_id, plan_id, start_date, expiry_date) VALUES 
(2, 2, DATE_SUB(CURDATE(), INTERVAL 1 MONTH), DATE_ADD(CURDATE(), INTERVAL 2 MONTH));
-- Amit: Basic (1 mo)
INSERT INTO memberships (member_id, plan_id, start_date, expiry_date) VALUES 
(3, 1, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH));
-- Sneha: Premium (12 mo) - Note: Expiry set to 3 days from now
INSERT INTO memberships (member_id, plan_id, start_date, expiry_date) VALUES 
(4, 3, DATE_SUB(CURDATE(), INTERVAL 362 DAY), DATE_ADD(CURDATE(), INTERVAL 3 DAY));
-- Vikram: Basic (1 mo) - Expired
INSERT INTO memberships (member_id, plan_id, start_date, expiry_date) VALUES 
(5, 1, DATE_SUB(CURDATE(), INTERVAL 2 MONTH), DATE_SUB(CURDATE(), INTERVAL 1 MONTH));

-- Seed Data (Payments)
INSERT INTO payments (member_id, membership_id, amount, payment_date, status, notes) VALUES 
(1, 1, 7200.00, DATE_SUB(CURDATE(), INTERVAL 6 MONTH), 'Paid', 'Paid via Card'),
(2, 2, 2100.00, DATE_SUB(CURDATE(), INTERVAL 1 MONTH), 'Paid', 'Paid via UPI'),
(3, 3, 800.00, CURDATE(), 'Paid', 'Cash Payment'),
(4, 4, 7200.00, DATE_SUB(CURDATE(), INTERVAL 362 DAY), 'Pending', 'Payment pending'),
(5, 5, 800.00, DATE_SUB(CURDATE(), INTERVAL 2 MONTH), 'Paid', 'Paid via Cash');

-- Calculate member status accurately after inserts
UPDATE members m
JOIN memberships mb ON m.member_id = mb.member_id
SET m.status = CASE
  WHEN mb.expiry_date < CURDATE() THEN 'Expired'
  WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 5 THEN 'Expiring Soon'
  ELSE 'Active'
END;
