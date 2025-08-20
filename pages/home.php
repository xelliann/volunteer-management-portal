<?php
<<<<<<< HEAD
=======
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

>>>>>>> 8a3025390cec50e494310adbfb983a08a0684839
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin(); // must be logged in to see

<<<<<<< HEAD
// Fetch all events
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If a join request is sent
if (isset($_GET['join_id'])) {
    $event_id = (int)$_GET['join_id'];
    $volunteer_id = $_SESSION['volunteer_id'];

    // Check if already joined
    $check = $pdo->prepare("SELECT id FROM volunteer_events WHERE volunteer_id = ? AND event_id = ?");
    $check->execute([$volunteer_id, $event_id]);

    if (!$check->fetch()) {
        $join = $pdo->prepare("INSERT INTO volunteer_events (volunteer_id, event_id) VALUES (?, ?)");
        $join->execute([$volunteer_id, $event_id]);
        echo "<div class='alert alert-success'>You have joined the event successfully!</div>";
    } else {
        echo "<div class='alert alert-error'>You have already joined this event.</div>";
=======
// Debug: Output POST and SESSION data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<pre style="background:#eee;padding:10px;">POST: ' . print_r($_POST, true) . '</pre>';
}
echo '<pre style="background:#eee;padding:10px;">SESSION: ' . print_r($_SESSION, true) . '</pre>';

// Generate CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch all events with vacancy
$stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// If a join or leave request is sent (POST)
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['csrf_token']) &&
    isset($_SESSION['volunteer_id'])
) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('<div class="alert alert-danger">Invalid CSRF token.</div>');
    }
    $volunteer_id = $_SESSION['volunteer_id'];

    // Join event
    if (isset($_POST['join_id'])) {
        $event_id = filter_var($_POST['join_id'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        if ($event_id) {
            // Check if already joined
            $check = $pdo->prepare("SELECT id FROM volunteer_events WHERE volunteer_id = ? AND event_id = ?");
            $check->execute([$volunteer_id, $event_id]);
            if (!$check->fetch()) {
                // Check vacancy
                $vacancyStmt = $pdo->prepare("SELECT vacancy FROM events WHERE id = ?");
                $vacancyStmt->execute([$event_id]);
                $vacancy = $vacancyStmt->fetchColumn();
                if ($vacancy > 0) {
                    $join = $pdo->prepare("INSERT INTO volunteer_events (volunteer_id, event_id) VALUES (?, ?)");
                    $join->execute([$volunteer_id, $event_id]);
                    $updateVacancy = $pdo->prepare("UPDATE events SET vacancy = vacancy - 1 WHERE id = ?");
                    $updateVacancy->execute([$event_id]);
                    echo "<div class='alert alert-success'>You have joined the event successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger'>No vacancies left for this event.</div>";
                }
            } else {
                echo "<div class='alert alert-error'>You have already joined this event.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Invalid event selected.</div>";
        }
    }
    // Leave event
    if (isset($_POST['leave_event_id'])) {
        $event_id = filter_var($_POST['leave_event_id'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        if ($event_id) {
            $del = $pdo->prepare("DELETE FROM volunteer_events WHERE volunteer_id = ? AND event_id = ?");
            $del->execute([$volunteer_id, $event_id]);
            $updateVacancy = $pdo->prepare("UPDATE events SET vacancy = vacancy + 1 WHERE id = ?");
            $updateVacancy->execute([$event_id]);
            echo "<div class='alert alert-success'>You have left the event and the vacancy is restored.</div>";
        }
>>>>>>> 8a3025390cec50e494310adbfb983a08a0684839
    }
}
?>

<<<<<<< HEAD
<h2>Available Events</h2>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>Title</th>
        <th>Description</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php if ($events): ?>
        <?php foreach ($events as $e): ?>
        <tr>
            <td><?= htmlspecialchars($e['title']) ?></td>
            <td><?= htmlspecialchars($e['description']) ?></td>
            <td><?= htmlspecialchars($e['event_date']) ?></td>
            <td>
                <a href="?join_id=<?= $e['id'] ?>" onclick="return confirm('Join this event?')">Join</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="4">No events available.</td></tr>
    <?php endif; ?>
</table>
=======

<h2 class="centered-heading">Available Events</h2>

<div class="events-flex-container">
    <?php if ($events): ?>
        <?php foreach ($events as $e): ?>
        <div class="event-row-flex">
            <div class="event-row-title"><?= htmlspecialchars($e['title']) ?></div>
            <div class="event-row-description"><?= htmlspecialchars($e['description']) ?></div>
            <div class="event-row-date"><strong>Date:</strong> <?= htmlspecialchars($e['date']) ?></div>
            <div class="event-row-location"><strong>Location:</strong> <?= htmlspecialchars($e['location'] ?? 'N/A') ?></div>
            <div class="event-row-vacancy"><strong>Vacancy:</strong> <?= isset($e['vacancy']) ? (int)$e['vacancy'] : 'N/A' ?></div>
            <div class="event-row-action">
                <form method="post">
                    <input type="hidden" name="join_id" value="<?= (int)$e['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <button type="submit" onclick="return confirm('Join this event?')" <?= (isset($e['vacancy']) && $e['vacancy'] <= 0) ? 'disabled' : '' ?>>Join</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="event-row-flex">No events available.</div>
    <?php endif; ?>
</div>
>>>>>>> 8a3025390cec50e494310adbfb983a08a0684839

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
