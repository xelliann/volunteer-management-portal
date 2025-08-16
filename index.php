<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/db.php';

// Fetch events
$stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch event IDs the user has already joined
$joinedEventIds = [];
if (isset($_SESSION['volunteer_id'])) {
    $volunteerId = $_SESSION['volunteer_id'];
    $joinedStmt = $pdo->prepare("SELECT event_id FROM volunteer_events WHERE volunteer_id = ?");
    $joinedStmt->execute([$volunteerId]);
    $joinedEventIds = $joinedStmt->fetchAll(PDO::FETCH_COLUMN, 0);
}

// Handle join request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join'], $_POST['csrf_token']) && isset($_SESSION['volunteer_id'])) {
    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('<div class="alert alert-danger">Invalid CSRF token.</div>');
    }

    // Validate event ID
    $eventId = filter_var($_POST['join'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    $volunteerId = $_SESSION['volunteer_id'];

    if ($eventId) {
        // Prevent duplicate joins
        $check = $pdo->prepare("SELECT 1 FROM volunteer_events WHERE volunteer_id=? AND event_id=?");
        $check->execute([$volunteerId, $eventId]);

        if (!$check->fetch()) {
            $insert = $pdo->prepare("INSERT INTO volunteer_events (volunteer_id, event_id) VALUES (?, ?)");
            $insert->execute([$volunteerId, $eventId]);
            // Decrement vacancy
            $updateVacancy = $pdo->prepare("UPDATE events SET vacancy = vacancy - 1 WHERE id = ? AND vacancy > 0");
            $updateVacancy->execute([$eventId]);
            echo "<div class='alert alert-success'>You have joined the event!</div>";
            // Auto-refresh to update joined status
            echo '<meta http-equiv="refresh" content="1">';
        } else {
            echo "<div class='alert alert-warning'>You already joined this event.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Invalid event selected.</div>";
    }
}
?>



<h2 class="centered-heading">Available Events</h2>
<div class="event-card-container">
    <?php if (empty($events)): ?>
        <div class="event-card">No events available.</div>
    <?php else: ?>
        <?php foreach ($events as $e): ?>
        <div class="event-card">
            <div class="event-card-title"><?= htmlspecialchars($e['title']) ?></div>
            <div class="event-card-description"><?= htmlspecialchars($e['description']) ?></div>
            <div class="event-card-details">
                <div class="event-card-detail"><strong>Date:</strong> <?= htmlspecialchars($e['date']) ?></div>
                <div class="event-card-detail"><strong>Location:</strong> <?= htmlspecialchars($e['location'] ?? 'N/A') ?></div>
                <div class="event-card-detail"><strong>Vacancy:</strong> <?= isset($e['vacancy']) ? (int)$e['vacancy'] : 'N/A' ?></div>
            </div>
            <div class="event-card-actions">
                <?php if (isset($_SESSION['volunteer_id'])): ?>
                <form method="post">
                    <input type="hidden" name="join" value="<?= (int)$e['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <button type="submit" onclick="return confirm('Join this event?')" 
                        <?= (isset($e['vacancy']) && $e['vacancy'] <= 0) || (in_array($e['id'], $joinedEventIds)) ? 'disabled' : '' ?>>
                        <?= in_array($e['id'], $joinedEventIds) ? 'Joined' : 'Join' ?>
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
