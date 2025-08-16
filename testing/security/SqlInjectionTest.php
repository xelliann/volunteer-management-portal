<?php
use PHPUnit\Framework\TestCase;

class SqlInjectionTest extends TestCase {
    public function testLoginSqlInjection() {
        $_POST['email'] = "' OR 1=1 -- ";
        $_POST['password'] = "anything";

        ob_start();
        include dirname(__DIR__, 2) . '/pages/login.php';
        $output = ob_get_clean();

        $this->assertStringNotContainsString("Dashboard", $output, "SQL Injection should not bypass login!");
    }
}
