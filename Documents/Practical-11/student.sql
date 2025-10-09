CREATE DATABASE IF NOT EXISTS college;
USE college;

CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dept_name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    age INT,
    department_id INT,
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

-- Insert some sample departments
INSERT INTO departments (dept_name) VALUES ('Computer Science'), ('Mechanical'), ('Civil');

-- Insert sample students
INSERT INTO students (name, age, department_id) VALUES
('Alice', 20, 1),
('Bob', 22, 2),
('Charlie', 21, 1);
