<?php
/**
 * Registration Class
 * Handles event registration operations
 */

require_once __DIR__ . '/../config.php';

class Registration {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Register user for an event
     */
    public function registerUserForEvent($userId, $eventId) {
        // Validate inputs
        if (!is_int($userId) || $userId <= 0 || !is_int($eventId) || $eventId <= 0) {
            throw new Exception("Invalid user or event ID");
        }

        // Check if user exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Database error");
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) {
            throw new Exception("User not found");
        }

        // Check if event exists
        $stmt = $this->db->prepare("SELECT id, date FROM events WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Database error");
        }

        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Event not found");
        }

        $event = $result->fetch_assoc();

        // Check if event date has passed
        if (strtotime($event['date']) < time()) {
            throw new Exception("Cannot register for past events");
        }

        // Check if already registered
        if ($this->isUserRegistered($userId, $eventId)) {
            throw new Exception("You are already registered for this event");
        }

        // Register user
        $stmt = $this->db->prepare("INSERT INTO registrations (user_id, event_id, registered_at) VALUES (?, ?, NOW())");
        if (!$stmt) {
            throw new Exception("Database error: " . $this->db->error);
        }

        $stmt->bind_param("ii", $userId, $eventId);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to register for event: " . $stmt->error);
        }

        return $this->db->insert_id;
    }

    /**
     * Unregister user from event
     */
    public function unregisterUserFromEvent($userId, $eventId) {
        $stmt = $this->db->prepare("DELETE FROM registrations WHERE user_id = ? AND event_id = ?");
        if (!$stmt) {
            throw new Exception("Database error");
        }

        $stmt->bind_param("ii", $userId, $eventId);
        return $stmt->execute();
    }

    /**
     * Check if user is registered for event
     */
    public function isUserRegistered($userId, $eventId) {
        $stmt = $this->db->prepare("SELECT id FROM registrations WHERE user_id = ? AND event_id = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("ii", $userId, $eventId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    /**
     * Get user's registered events
     */
    public function getUserRegistrations($userId) {
        $stmt = $this->db->prepare("
            SELECT e.id, e.title, e.description, e.date, e.venue, r.registered_at
            FROM registrations r
            JOIN events e ON r.event_id = e.id
            WHERE r.user_id = ?
            ORDER BY e.date ASC
        ");

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $registrations = [];
        while ($row = $result->fetch_assoc()) {
            $registrations[] = $row;
        }

        return $registrations;
    }

    /**
     * Get event participants (admin only)
     */
    public function getEventParticipants($eventId) {
        $stmt = $this->db->prepare("
            SELECT u.id, u.name, u.email, u.role, r.registered_at
            FROM registrations r
            JOIN users u ON r.user_id = u.id
            WHERE r.event_id = ?
            ORDER BY r.registered_at DESC
        ");

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $result = $stmt->get_result();

        $participants = [];
        while ($row = $result->fetch_assoc()) {
            $participants[] = $row;
        }

        return $participants;
    }

    /**
     * Export participant data (admin only)
     */
    public function exportParticipantsToCSV($eventId) {
        $participants = $this->getEventParticipants($eventId);

        if (empty($participants)) {
            throw new Exception("No participants found");
        }

        // Get event details
        $stmt = $this->db->prepare("SELECT title FROM events WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Database error");
        }

        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $result = $stmt->get_result();
        $event = $result->fetch_assoc();

        // Prepare CSV data
        $filename = "participants_" . $event['title'] . "_" . date('Y-m-d_H-i-s') . ".csv";
        $filepath = __DIR__ . "/../exports/" . $filename;

        // Create exports directory if not exists
        if (!is_dir(__DIR__ . "/../exports")) {
            mkdir(__DIR__ . "/../exports", 0755, true);
        }

        $file = fopen($filepath, 'w');

        if (!$file) {
            throw new Exception("Failed to create export file");
        }

        // Write header
        fputcsv($file, ['Name', 'Email', 'Role', 'Registered At']);

        // Write data
        foreach ($participants as $participant) {
            fputcsv($file, [
                $participant['name'],
                $participant['email'],
                $participant['role'],
                $participant['registered_at']
            ]);
        }

        fclose($file);

        return $filename;
    }

    /**
     * Get statistics (admin only)
     */
    public function getStatistics() {
        $stats = [];

        // Total events
        $result = $this->db->query("SELECT COUNT(*) as count FROM events");
        $stats['total_events'] = $result->fetch_assoc()['count'];

        // Total users
        $result = $this->db->query("SELECT COUNT(*) as count FROM users");
        $stats['total_users'] = $result->fetch_assoc()['count'];

        // Total registrations
        $result = $this->db->query("SELECT COUNT(*) as count FROM registrations");
        $stats['total_registrations'] = $result->fetch_assoc()['count'];

        // Upcoming events
        $result = $this->db->query("SELECT COUNT(*) as count FROM events WHERE date >= NOW()");
        $stats['upcoming_events'] = $result->fetch_assoc()['count'];

        // Past events
        $result = $this->db->query("SELECT COUNT(*) as count FROM events WHERE date < NOW()");
        $stats['past_events'] = $result->fetch_assoc()['count'];

        return $stats;
    }
}
?>
