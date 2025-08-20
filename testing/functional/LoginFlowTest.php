<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../config/db.php'; // This sets $pdo

class LoginFlowTest extends TestCase {
    public function testLoginWithValidCredentials() {
        global $pdo;

        $pdo->exec("DELETE FROM users WHERE email='login_test@example.com'");
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([
            "Login User",
            "login_test@example.com",
            password_hash("mypassword", PASSWORD_BCRYPT)
        ]);

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute(["login_test@example.com"]);
        $user = $stmt->fetch();

        $this->assertTrue(password_verify("mypassword", $user['password']), "Password should match and login should succeed");
    }
}
