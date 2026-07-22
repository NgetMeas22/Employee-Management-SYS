/*
USE meas_sys;

-- Admin Login==========================

CREATE TABLE IF NOT EXISTS user_s (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    pwd VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'admin'
);
DROP TABLE users_s;
SELECT * FROM user_s;

-- One active login code is kept per user. Run this after creating user_s.
CREATE TABLE IF NOT EXISTS user_otp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    otp_code VARCHAR(6) NOT NULL,
    expired_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user_s(id)
        ON UPDATE CASCADE ON DELETE CASCADE
);

-- ======================================


-- =====================================
-- Table: departments

CREATE TABLE departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,	
    department_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

SELECT * FROM departments;
-- =====================================



-- =====================================
-- Table: employees

CREATE TABLE employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_code VARCHAR(20) UNIQUE,
    department_id INT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    gender ENUM('Male','Female') NOT NULL,
    dob DATE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    salary DECIMAL(10,2) NOT NULL,
    address TEXT,
    photo VARCHAR(255),
    hire_date DATE NOT NULL,
    status ENUM(
        'Active',
        'Inactive',
        'On Leave',
        'Resigned'
    ) DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_department
    FOREIGN KEY (department_id)
    REFERENCES departments(department_id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);
SHOW TABLES LIKE 'employees';

DESC employees;
SELECT * FROM employees;
-- =====================================


DROP TABLE employees

*/
