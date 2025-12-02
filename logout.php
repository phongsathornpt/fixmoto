<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_destroy();
    header("Location: index.php");
    exit;
}
// Redirect GET requests to index
header("Location: index.php");
exit;
?>