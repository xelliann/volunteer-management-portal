<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

// TEMP: Make yourself admin manually (set after login in login.php if needed)
if (empty($_SESSION['is_admin'])) {
    echo "<p style='color:red;'>Access Denied: Admins only.</p>";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$errors = [];
$success = "";

// CREATE or UPDATE Event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $date = $_POST['date'] ?? '';
    $vacancy = $_POST['vacancy'] ?? '';
    $event_id = $_POST['event_id'] ?? '';

    if ($title === '' || $date === '' || $vacancy === '') {
        $errors[] = "Title, date, and vacancy are required.";
    }

    if (empty($errors)) {
        if ($event_id) {
            // Update existing
            $stmt = $pdo->prepare("UPDATE events SET title=?, description=?, date=?, vacancy=? WHERE id=?");
            $stmt->execute([$title, $description, $date, $vacancy, $event_id]);
            $success = "Event updated successfully!";
        } else {
            // Create new
            $stmt = $pdo->prepare("INSERT INTO events (title, description, date, vacancy) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, $date, $vacancy]);
            $success = "Event created successfully!";
        }
    }
}

// DELETE Event
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $pdo->prepare("DELETE FROM events WHERE id = ?")->execute([$del_id]);
    $success = "Event deleted successfully!";
}

// Fetch events
$stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If editing
$editData = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$edit_id]);
    $editData = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<h2>Manage Events</h2>

<?php if ($errors): ?>
<div class="alert alert-error">
    <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="post" action="">
    <input type="hidden" name="event_id" value="<?= htmlspecialchars($editData['id'] ?? '') ?>">
    <label>Title
        <input type="text" name="title" required value="<?= htmlspecialchars($editData['title'] ?? '') ?>">
    </label>
    <label>Description
        <textarea name="description"><?= htmlspecialchars($editData['description'] ?? '') ?></textarea>
    </label>
    <label>Event Date
        <input type="date" name="date" required value="<?= htmlspecialchars($editData['date'] ?? '') ?>">
    </label>
    <label>Vacancy
        <input type="number" name="vacancy" min="0" required value="<?= htmlspecialchars($editData['vacancy'] ?? '') ?>">
    </label>
    <button type="submit"><?= $editData ? 'Update Event' : 'Create Event' ?></button>
</form>

<hr>

<h3>All Events</h3>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Date</th>
        <th>Vacancy</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($events as $ev): ?>
    <tr>
        <td><?= htmlspecialchars($ev['title']) ?></td>
        <td><?= htmlspecialchars($ev['description']) ?></td>
        <td><?= htmlspecialchars($ev['date']) ?></td>
        <td><?= htmlspecialchars($ev['vacancy'] ?? 'N/A') ?></td>
        <td>
            <a href="?edit_id=<?= $ev['id'] ?>">Edit</a> |
            <a href="?delete_id=<?= $ev['id'] ?>" onclick="return confirm('Delete this event?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
