<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/db.php';

// Fetch events
$stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle join request
if (isset($_GET['join']) && isset($_SESSION['volunteer_id'])) {
    $eventId = (int) $_GET['join'];
    $volunteerId = $_SESSION['volunteer_id'];

    // Prevent duplicate joins
    $check = $pdo->prepare("SELECT 1 FROM volunteer_events WHERE volunteer_id=? AND event_id=?");
    $check->execute([$volunteerId, $eventId]);

    if (!$check->fetch()) {
        $insert = $pdo->prepare("INSERT INTO volunteer_events (volunteer_id, event_id) VALUES (?, ?)");
        $insert->execute([$volunteerId, $eventId]);
        echo "<div class='alert alert-success'>You have joined the event!</div>";
    } else {
        echo "<div class='alert alert-warning'>You already joined this event.</div>";
    }
}
?>

<h2>Available Events</h2>
<?php if (empty($events)): ?>
    <p>No events available at the moment.</p>
<?php else: ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th>Location</th>
            <?php if (isset($_SESSION['volunteer_id'])): ?><th>Action</th><?php endif; ?>
        </tr>
        <?php foreach ($events as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['title']) ?></td>
                <td><?= htmlspecialchars($e['description']) ?></td>
                <td><?= htmlspecialchars($e['date']) ?></td>
                <td><?= htmlspecialchars($e['location']) ?></td>
                <?php if (isset($_SESSION['volunteer_id'])): ?>
                    <td><a href="?join=<?= $e['id'] ?>">Join</a></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
