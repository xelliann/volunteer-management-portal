<?php
use PHPUnit\Framework\TestCase;

// Include the database configuration
require_once __DIR__ . '/../../config/db.php'; // This sets $pdo

class UserRegistrationTest extends TestCase {
    protected function setUp(): void {
        global $pdo;
        // Clean up any existing test user
        $pdo->exec("DELETE FROM users WHERE email='testuser@example.com'");
    }

    public function testUserRegistration() {
        global $pdo;

        // Insert a test user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $result = $stmt->execute([
            "Test User",
            "testuser@example.com",
            password_hash("password123", PASSWORD_BCRYPT)
        ]);

        $this->assertTrue($result, "User registration should succeed");

        // Verify the user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute(["testuser@example.com"]);
        $user = $stmt->fetch();

        $this->assertNotEmpty($user, "User should exist in the database after registration");
    }
}
