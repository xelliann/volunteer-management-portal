<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Provide a valid email.";
    }
    if ($password === '') {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id, password, name FROM volunteers WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['volunteer_id'] = $user['id'];
            $_SESSION['volunteer_name'] = $user['name'];

            // TEMP: Make this user an admin if their email matches
            if ($email === 'admin@example.com') {
                $_SESSION['is_admin'] = true;
            }

            header("Location: /volunteer_portal/index.php");
            exit;
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>

<h2>Login</h2>

<?php if ($errors): ?>
  <div class="alert alert-error">
    <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<<<<<<< HEAD
=======

>>>>>>> 8a3025390cec50e494310adbfb983a08a0684839
<form id="loginForm" method="post" action="">
  <label>Email
    <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
  </label>
  <label>Password
    <input type="password" name="password" required>
  </label>
  <button type="submit">Login</button>
</form>

<<<<<<< HEAD
=======
<div style="text-align:center; margin-top:18px;">
  <a href="/volunteer_portal/pages/admin_login.php" class="admin-login-btn">Login as Admin</a>
</div>

>>>>>>> 8a3025390cec50e494310adbfb983a08a0684839
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
