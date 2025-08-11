<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin(); // must be logged in to see

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
    }
}
?>

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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
