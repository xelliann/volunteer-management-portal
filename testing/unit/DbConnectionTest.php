<?php
use PHPUnit\Framework\TestCase;

// Include the database configuration
require_once __DIR__ . '/../../config/db.php'; // This sets $pdo

class DbConnectionTest extends TestCase {
    public function testDatabaseConnection() {
        global $pdo;
        $this->assertInstanceOf(PDO::class, $pdo, "Database connection should return a PDO instance");
    }

    public function testDatabaseIsConnected() {
        global $pdo;
        $stmt = $pdo->query("SELECT 1");
        $this->assertEquals(1, $stmt->fetchColumn(), "Database should respond with 1");
    }

    public function testTruncateUsersTable() {
        global $pdo;
        $pdo->exec("TRUNCATE users");
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        $this->assertEquals(0, $stmt->fetchColumn(), "Users table should be empty after truncation");
    }
}
