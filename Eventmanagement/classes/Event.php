<?php
/**
 * Event Class
 * Handles event management operations
 */

require_once __DIR__ . '/../config.php';

class Event {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Create a new event (admin only)
     */
    public function createEvent($title, $description, $date, $venue) {
        $title = sanitizeInput($title);
        $description = sanitizeInput($description);
        $venue = sanitizeInput($venue);

        if (empty($title) || empty($description) || empty($date) || empty($venue)) {
            throw new Exception("All fields are required");
        }

        // Validate date format and ensure it's in future
        $eventDate = new DateTime($date);
        $now = new DateTime();

        if ($eventDate < $now) {
            throw new Exception("Event date must be in the future");
        }

        $stmt = $this->db->prepare("INSERT INTO events (title, description, date, venue) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database error: " . $this->db->error);
        }

        $stmt->bind_param("ssss", $title, $description, $date, $venue);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to create event: " . $stmt->error);
        }

        return $this->db->insert_id;
    }

    /**
     * Get all events
     */
    public function getAllEvents($upcomingOnly = false) {
        $query = "SELECT id, title, description, date, venue, created_at FROM events";
        
        if ($upcomingOnly) {
            $query .= " WHERE date >= NOW()";
        }

        $query .= " ORDER BY date ASC";

        $result = $this->db->query($query);
        
        $events = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $row['participant_count'] = $this->getParticipantCount($row['id']);
                $events[] = $row;
            }
        }

        return $events;
    }

    /**
     * Get event by ID
     */
    public function getEventById($id) {
        $stmt = $this->db->prepare("SELECT id, title, description, date, venue, created_at FROM events WHERE id = ?");
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $event = $result->fetch_assoc();
        $event['participant_count'] = $this->getParticipantCount($event['id']);

        return $event;
    }

    /**
     * Update event (admin only)
     */
    public function updateEvent($id, $title, $description, $date, $venue) {
        $title = sanitizeInput($title);
        $description = sanitizeInput($description);
        $venue = sanitizeInput($venue);

        if (empty($title) || empty($description) || empty($date) || empty($venue)) {
            throw new Exception("All fields are required");
        }

        $stmt = $this->db->prepare("UPDATE events SET title = ?, description = ?, date = ?, venue = ? WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Database error");
        }

        $stmt->bind_param("ssssi", $title, $description, $date, $venue, $id);
        return $stmt->execute();
    }

    /**
     * Delete event (admin only)
     */
    public function deleteEvent($id) {
        // Delete registrations first
        $stmt = $this->db->prepare("DELETE FROM registrations WHERE event_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
        }

        // Delete event
        $stmt = $this->db->prepare("DELETE FROM events WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Database error");
        }

        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Get participant count for an event
     */
    public function getParticipantCount($eventId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM registrations WHERE event_id = ?");
        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['count'] ?? 0;
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
}
?>
