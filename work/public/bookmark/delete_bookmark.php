<?php
session_start();
require(__DIR__ . "/../../app/db.php");

if (!isset($_SESSION["user_id"])) {
    echo "ログインしてください";
    echo "<a href='../auth/login.php'>ログイン画面へ</a>";
    exit;
}

if (!isset($_GET["id"])) {
    echo "ブックマークIDがありません";
    echo "<a href='../index.php'>ブックマーク一覧へ </a>";
    exit;
}

$id = $_GET["id"];
$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("DELETE FROM bookmarks WHERE id = ? AND user_id = ?");
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if($stmt->execute([$id, $user_id])): ?>
        <a href="../index.php">削除しました！ 一覧へ</a>
    <?php else:?>
        <a href="../index.php">削除に失敗しました。 一覧へ</a>
    <?php endif;?>
</body>
</html>