<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // server-side validation
    if ($name === '') $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if ($phone !== '' && !preg_match('/^\+?\d{7,15}$/', $phone)) $errors[] = "Phone must be digits (7-15 chars).";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";

    if (empty($errors)) {
        // check duplicate email
        $stmt = $pdo->prepare("SELECT id FROM volunteers WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Email already registered. Try logging in.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare("INSERT INTO volunteers (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $ins->execute([$name, $email, $phone, $hash]);
            $success = "Registration successful. You can now <a href='login.php'>login</a>.";
        }
    }
}
?>

<h2>Register</h2>

<?php if ($errors): ?>
  <div class="alert alert-error">
    <ul>
      <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php if ($success): ?>
  <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form id="registerForm" method="post" action="">
  <label>Name
    <input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
  </label>
  <label>Email
    <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
  </label>
  <label>Phone
    <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
  </label>
  <label>Password
    <input type="password" name="password" required>
  </label>
  <label>Confirm Password
    <input type="password" name="confirm_password" required>
  </label>
  <button type="submit">Register</button>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
