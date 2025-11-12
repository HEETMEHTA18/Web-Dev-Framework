<?php
/**
 * User Class
 * Handles user authentication and profile management
 */

require_once __DIR__ . '/../config.php';

class User {
    private $db;
    private $id;
    private $name;
    private $email;
    private $role;
    private $created_at;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Register a new user
     */
    public function register($name, $email, $password, $password_confirm) {
        // Validation
        $name = sanitizeInput($name);
        $email = sanitizeInput($email);

        if (empty($name) || empty($email) || empty($password)) {
            throw new Exception("All fields are required");
        }

        if (strlen($password) < PASSWORD_MIN_LENGTH) {
            throw new Exception("Password must be at least " . PASSWORD_MIN_LENGTH . " characters");
        }

        if ($password !== $password_confirm) {
            throw new Exception("Passwords do not match");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Check if email already exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $this->db->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            throw new Exception("Email already registered");
        }

        // Hash password
        $password_hash = hashPassword($password);

        // Insert new user
        $role = 'user'; // Default role
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $this->db->error);
        }

        $stmt->bind_param("ssss", $name, $email, $password_hash, $role);
        
        if (!$stmt->execute()) {
            throw new Exception("Registration failed: " . $stmt->error);
        }

        return $this->db->insert_id;
    }

    /**
     * Login user
     */
    public function login($email, $password, $rememberMe = false) {
        $email = sanitizeInput($email);

        if (empty($email) || empty($password)) {
            throw new Exception("Email and password are required");
        }

        // Fetch user by email
        $stmt = $this->db->prepare("SELECT id, name, email, password_hash, role FROM users WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Database error: " . $this->db->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Invalid email or password");
        }

        $user = $result->fetch_assoc();

        // Verify password
        if (!verifyPassword($password, $user['password_hash'])) {
            throw new Exception("Invalid email or password");
        }

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_time'] = time();

        // Remember Me functionality
        if ($rememberMe) {
            $rememberToken = bin2hex(random_bytes(32));
            $expiryDate = date('Y-m-d H:i:s', strtotime('+30 days'));

            $stmt = $this->db->prepare("UPDATE users SET remember_token = ?, token_expiry = ? WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("ssi", $rememberToken, $expiryDate, $user['id']);
                $stmt->execute();
            }

            setcookie('remember_token', $rememberToken, time() + (30 * 24 * 60 * 60), '/', '', HTTPS_ENABLED, true);
        }

        return true;
    }

    /**
     * Auto-login using remember token
     */
    public function autoLogin($token) {
        $stmt = $this->db->prepare("SELECT id, name, email, password_hash, role FROM users WHERE remember_token = ? AND token_expiry > NOW()");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return false;
        }

        $user = $result->fetch_assoc();

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_time'] = time();

        return true;
    }

    /**
     * Logout user
     */
    public function logout() {
        // Clear remember token
        if (isset($_SESSION['user_id'])) {
            $stmt = $this->db->prepare("UPDATE users SET remember_token = NULL, token_expiry = NULL WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
            }
        }

        // Clear session
        session_destroy();

        // Clear remember me cookie
        setcookie('remember_token', '', time() - 3600, '/');

        return true;
    }

    /**
     * Get user by ID
     */
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT id, name, email, role, created_at FROM users WHERE id = ?");
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    /**
     * Update user profile
     */
    public function updateProfile($id, $name, $email) {
        $name = sanitizeInput($name);
        $email = sanitizeInput($email);

        if (empty($name) || empty($email)) {
            throw new Exception("Name and email are required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Check if email exists for other users
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        if (!$stmt) {
            throw new Exception("Database error");
        }

        $stmt->bind_param("si", $email, $id);
        $stmt->execute();

        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("Email already in use");
        }

        // Update user
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Database error");
        }

        $stmt->bind_param("ssi", $name, $email, $id);
        return $stmt->execute();
    }

    /**
     * Change password
     */
    public function changePassword($id, $oldPassword, $newPassword, $newPasswordConfirm) {
        // Validate
        if (strlen($newPassword) < PASSWORD_MIN_LENGTH) {
            throw new Exception("New password must be at least " . PASSWORD_MIN_LENGTH . " characters");
        }

        if ($newPassword !== $newPasswordConfirm) {
            throw new Exception("Passwords do not match");
        }

        // Get user
        $stmt = $this->db->prepare("SELECT password_hash FROM users WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Database error");
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("User not found");
        }

        $user = $result->fetch_assoc();

        // Verify old password
        if (!verifyPassword($oldPassword, $user['password_hash'])) {
            throw new Exception("Current password is incorrect");
        }

        // Update password
        $newPasswordHash = hashPassword($newPassword);
        $stmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Database error");
        }

        $stmt->bind_param("si", $newPasswordHash, $id);
        return $stmt->execute();
    }

    /**
     * Get all users (admin only)
     */
    public function getAllUsers() {
        $query = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC";
        $result = $this->db->query($query);
        
        $users = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        return $users;
    }
}
?>
