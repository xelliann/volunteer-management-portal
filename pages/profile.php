<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$volunteer = getCurrentVolunteer($pdo);

// Fetch joined events
$stmt = $pdo->prepare("
    SELECT e.title, e.description, e.event_date, ve.joined_at
    FROM volunteer_events ve
    JOIN events e ON ve.event_id = e.id
    WHERE ve.volunteer_id = ?
    ORDER BY e.event_date ASC
");
$stmt->execute([$_SESSION['volunteer_id']]);
$joinedEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>My Profile</h2>
<p><strong>Name:</strong> <?= htmlspecialchars($volunteer['name']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($volunteer['email']) ?></p>
<p><strong>Phone:</strong> <?= htmlspecialchars($volunteer['phone']) ?></p>
<hr>

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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
