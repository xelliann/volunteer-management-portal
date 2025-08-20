<?php
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo "<p>Access Denied: Admins only.</p>";
    exit;
}
