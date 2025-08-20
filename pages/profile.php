<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$volunteer = getCurrentVolunteer($pdo);

// Fetch joined events
$stmt = $pdo->prepare("
<<<<<<< HEAD
    SELECT e.title, e.description, e.event_date, ve.joined_at
    FROM volunteer_events ve
    JOIN events e ON ve.event_id = e.id
    WHERE ve.volunteer_id = ?
    ORDER BY e.event_date ASC
");
$stmt->execute([$_SESSION['volunteer_id']]);
$joinedEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
=======
    SELECT e.id, e.title, e.description, e.date, ve.joined_at
    FROM volunteer_events ve
    JOIN events e ON ve.event_id = e.id
    WHERE ve.volunteer_id = ?
    ORDER BY e.date ASC
");
$stmt->execute([$_SESSION['volunteer_id']]);
$joinedEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle leave event request
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['leave_event_id'], $_POST['csrf_token']) &&
    isset($_SESSION['volunteer_id'])
) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        echo '<div class="alert alert-danger">Invalid CSRF token.</div>';
    } else {
        $leave_event_id = filter_var($_POST['leave_event_id'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        $volunteer_id = $_SESSION['volunteer_id'];
        if ($leave_event_id) {
            $del = $pdo->prepare("DELETE FROM volunteer_events WHERE volunteer_id = ? AND event_id = ?");
            $del->execute([$volunteer_id, $leave_event_id]);
            // Increment vacancy when leaving
            $updateVacancy = $pdo->prepare("UPDATE events SET vacancy = vacancy + 1 WHERE id = ?");
            $updateVacancy->execute([$leave_event_id]);
            echo '<div class="alert alert-success">You have left the event and the vacancy is restored.</div>';
            // Optionally, refresh the page to update the list
            echo '<meta http-equiv="refresh" content="1">';
        } else {
            echo '<div class="alert alert-danger">Invalid event selected.</div>';
        }
    }
}
>>>>>>> 8a3025390cec50e494310adbfb983a08a0684839
?>

<h2>My Profile</h2>
<p><strong>Name:</strong> <?= htmlspecialchars($volunteer['name']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($volunteer['email']) ?></p>
<p><strong>Phone:</strong> <?= htmlspecialchars($volunteer['phone']) ?></p>
<hr>

<<<<<<< HEAD
<h3>My Joined Events</h3>
<?php if ($joinedEvents): ?>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Date</th>
        <th>Joined At</th>
    </tr>
    <?php foreach ($joinedEvents as $ev): ?>
    <tr>
        <td><?= htmlspecialchars($ev['title']) ?></td>
        <td><?= htmlspecialchars($ev['description']) ?></td>
        <td><?= htmlspecialchars($ev['event_date']) ?></td>
        <td><?= htmlspecialchars($ev['joined_at']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p>You have not joined any events yet.</p>
<?php endif; ?>
=======

<h3>My Joined Events</h3>
<div class="events-flex-container">
<?php if ($joinedEvents): ?>
    <?php foreach ($joinedEvents as $ev): ?>
    <div class="event-row-flex">
        <div class="event-row-title"><?= htmlspecialchars($ev['title']) ?></div>
        <div class="event-row-description"><?= htmlspecialchars($ev['description']) ?></div>
        <div class="event-row-date"><strong>Date:</strong> <?= htmlspecialchars($ev['date']) ?></div>
        <div class="event-row-joined"><strong>Joined At:</strong> <?= htmlspecialchars($ev['joined_at']) ?></div>
        <div class="event-row-action">
            <form method="post">
                <input type="hidden" name="leave_event_id" value="<?= (int)$ev['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <button type="submit" onclick="return confirm('Are you sure you want to leave this event?')">Leave</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="event-row-flex">You have not joined any events yet.</div>
<?php endif; ?>
</div>
>>>>>>> 8a3025390cec50e494310adbfb983a08a0684839

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
