<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/db.php';

if (empty($_SESSION['is_admin'])) {
    echo "<p class='alert alert-error'>Access Denied: Admins only.</p>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$event_id = $_GET['id'] ?? null;
if (!$event_id) {
    echo "<p class='alert alert-error'>No event ID provided.</p>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "<p class='alert alert-error'>Event not found.</p>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $date = $_POST['event_date'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if ($name === '') $errors[] = "Event name is required.";
    if ($date === '') $errors[] = "Event date is required.";

    if (empty($errors)) {
        $update = $pdo->prepare("UPDATE events SET name=?, event_date=?, description=? WHERE id=?");
        $update->execute([$name, $date, $description, $event_id]);

        header("Location: manage_events.php");
        exit;
    }
}
?>

<h2>Edit Event</h2>

<?php if ($errors): ?>
<div class="alert alert-error">
    <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<form method="post">
    <label>Event Name
        <input type="text" name="name" value="<?= htmlspecialchars($event['name']) ?>" required>
    </label>
    <label>Event Date
        <input type="date" name="event_date" value="<?= htmlspecialchars($event['event_date']) ?>" required>
    </label>
    <label>Description
        <textarea name="description"><?= htmlspecialchars($event['description']) ?></textarea>
    </label>
    <button type="submit">Update Event</button>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
