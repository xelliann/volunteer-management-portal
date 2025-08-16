<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Volunteer Portal</title>
  <link rel="stylesheet" href="/volunteer_portal/assets/css/style.css">
  <script defer src="/volunteer_portal/assets/js/script.js"></script>
</head>
<body>
<header>
  <nav>
    <a href="/volunteer_portal/index.php">Home</a> |
    <?php if (!empty($_SESSION['volunteer_id'])): ?>
      <a href="/volunteer_portal/pages/profile.php">Profile</a> |
      <a href="/volunteer_portal/pages/logout.php">Logout</a>
    <?php else: ?>
      <a href="/volunteer_portal/pages/login.php">Login</a> |
      <a href="/volunteer_portal/pages/register.php">Register</a>
    <?php endif; ?>
  </nav>
</header>
<main>
