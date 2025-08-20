<?php
use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 2) . '/includes/auth.php';

class AuthTest extends TestCase {
    public function testPasswordHashing() {
        $password = "secret123";
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $this->assertTrue(password_verify($password, $hash));
    }
}
