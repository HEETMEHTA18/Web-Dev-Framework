<?php
/**
 * Database Setup Script
 * Creates tables and initializes the database
 */

$servername = "localhost:3307";
$username = "root";
$password = "";

// Create connection without database
$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$dbname = "event_management";

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS " . $dbname;

if ($conn->query($sql) === TRUE) {
    echo "Database created or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select database
$conn->select_db($dbname);
$conn->set_charset("utf8mb4");

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    remember_token VARCHAR(64) NULL,
    token_expiry DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating table 'users': " . $conn->error . "<br>";
}

// Create events table
$sql = "CREATE TABLE IF NOT EXISTS events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date DATETIME NOT NULL,
    venue VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_date (date),
    INDEX idx_title (title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table 'events' created successfully.<br>";
} else {
    echo "Error creating table 'events': " . $conn->error . "<br>";
}

// Create registrations table
$sql = "CREATE TABLE IF NOT EXISTS registrations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_registration (user_id, event_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_event (event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "Table 'registrations' created successfully.<br>";
} else {
    echo "Error creating table 'registrations': " . $conn->error . "<br>";
}

// Create default admin user
$name = "Admin";
$email = "admin@charusat.edu";
$password_hash = password_hash("Admin@123456", PASSWORD_BCRYPT, ['cost' => 12]);
$role = "admin";

// Check if admin already exists
$result = $conn->query("SELECT id FROM users WHERE email = '$email'");

if ($result->num_rows === 0) {
    $sql = "INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("ssss", $name, $email, $password_hash, $role);
        if ($stmt->execute()) {
            echo "Default admin user created successfully.<br>";
            echo "Admin Email: " . $email . "<br>";
            echo "Admin Password: Admin@123456<br>";
        } else {
            echo "Error creating admin user: " . $stmt->error . "<br>";
        }
    }
} else {
    echo "Admin user already exists.<br>";
}

echo "<br><strong>Database setup completed successfully!</strong><br>";
echo "<a href='login.php'>Go to Login Page</a>";

$conn->close();
?>
