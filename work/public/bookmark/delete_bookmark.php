<?php
session_start();
require(__DIR__ . "/../../app/db.php");
define("SITE_URL","http://localhost:8080");
$id = $_GET["id"];
$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("DELETE FROM bookmarks WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
header("Location: " . SITE_URL . "/index.php");
?>
