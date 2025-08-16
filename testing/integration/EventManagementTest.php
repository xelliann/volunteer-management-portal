<?php
use PHPUnit\Framework\TestCase;

// Include the database configuration
require_once __DIR__ . '/../../config/db.php'; // This sets $pdo

class EventManagementTest extends TestCase {
    protected function setUp(): void {
        global $pdo;
        // Clean up any existing test event
        $pdo->exec("DELETE FROM events WHERE title='Test Event'");
    }

    public function testEventCreation() {
        global $pdo;

        // Insert a test event
        $stmt = $pdo->prepare("INSERT INTO events (title, description, date) VALUES (?, ?, ?)");
        $result = $stmt->execute([
            "Test Event",
            "This is a test event",
            "2025-08-20"
        ]);

        $this->assertTrue($result, "Event should be inserted into the database");

        // Verify the event exists
        $stmt = $pdo->prepare("SELECT * FROM events WHERE title=?");
        $stmt->execute(["Test Event"]);
        $event = $stmt->fetch();

        $this->assertNotEmpty($event, "Event should exist after creation");
    }
}
