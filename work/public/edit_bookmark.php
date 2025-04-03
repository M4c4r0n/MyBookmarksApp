<?php
session_start();
require(__DIR__ . "/../app/db.php");
require(__DIR__ . "/../app/functions.php");

if (!isset($_SESSION["user_id"])) {
    echo "ログインしてください";
    echo "<a href='login.php'>ログイン画面へ</a>";
    exit;
}

if (!isset($_GET["id"])) {
    echo "ブックマークIDがありません";
    echo "<a href='index.php'>ブックマーク一覧へ </a>";
    exit;
}

$id = $_GET["id"];
$user_id = $_SESSION["user_id"];
$stmt = $pdo->prepare("SELECT * FROM bookmarks WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$bookmark = $stmt->fetch();

if (!$bookmark) {
    echo "編集できません";
    echo "<a href='index.php'>ブックマーク一覧へ </a>";
    exit;
}

$stmt = $pdo->prepare("
    SELECT tags.name FROM tags
    JOIN bookmark_tags ON tags.id = bookmark_tags.tag_id
    WHERE bookmark_tags.bookmark_id = ?
");
$stmt->execute([$id]);
$tags = $stmt->fetchAll(PDO::FETCH_COLUMN);
$tags_text = implode(", ", $tags); // カンマ区切りにする
?>
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>editBookmark</title>
</head>
<body>
    <form action="update_bookmark.php" method="POST">
        <input type="hidden" name="id" value="<?= $bookmark->id; ?>">
        <input type="text" name="title" value="<?= h($bookmark->title); ?>" required>
        <input type="url" name="url" value="<?= h($bookmark->url); ?>" required>
        <textarea name="description"><?= h($bookmark->description); ?></textarea>
        <input type="text" name="tags" value="<?=h($tags_text);?>">
        <button type="submit">更新</button>
    </form>
</body>
</html>