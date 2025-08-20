<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM volunteers WHERE email = ? AND is_admin = 1 LIMIT 1");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['volunteer_id'] = $admin['id'];
        $_SESSION['is_admin'] = 1;
        header('Location: /volunteer_portal/pages/manage_events.php');
        exit;
    } else {
        $error = 'Invalid admin credentials.';
    }
}
?>

<h2>Admin Login</h2>
<?php if ($error): ?>
    <div class="alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post">
    <label>Email:<br>
        <input type="email" name="email" required>
    </label>
    <label>Password:<br>
        <input type="password" name="password" required>
    </label>
    <button type="submit">Login as Admin</button>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>