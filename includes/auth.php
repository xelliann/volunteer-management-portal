<?php
require_once __DIR__ . '/../config/db.php';

function isLoggedIn() {
    return !empty($_SESSION['volunteer_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /volunteer_portal/pages/login.php");
        exit;
    }
}

function getCurrentVolunteer($pdo) {
    if (empty($_SESSION['volunteer_id'])) return null;
    $stmt = $pdo->prepare("SELECT id, name, email, phone, created_at FROM volunteers WHERE id = ?");
    $stmt->execute([$_SESSION['volunteer_id']]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
