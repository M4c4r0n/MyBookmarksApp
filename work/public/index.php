<?php

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: http://localhost:8080/auth/login.php");
    exit;
}
require(__DIR__ . "/../app/functions.php");
require(__DIR__ . "/../app/db.php");
echo "ようこそ, " . $_SESSION["username"] . " さん！";
echo "<a href='./auth/logout.php'>ログアウト</a>";

$user_id = $_SESSION["user_id"];
if(isset($_GET["q"])){
    $search = $_GET["q"];
    $sql = "
        SELECT DISTINCT bookmarks.*
        FROM bookmarks
        LEFT JOIN bookmark_tags ON bookmarks.id = bookmark_tags.bookmark_id
        LEFT JOIN tags ON bookmark_tags.tag_id = tags.id
        WHERE bookmarks.user_id = ?
    ";

    $params = [$user_id];

    if ($search) {
        $sql .= " AND (bookmarks.title LIKE ? OR bookmarks.description LIKE ? OR tags.name LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $bookmarks = $stmt->fetchAll();
}
else if(isset($_GET["tag"])){
    $tag = $_GET["tag"];
    $stmt = $pdo->prepare("
        SELECT bookmarks.* FROM bookmarks
        JOIN bookmark_tags ON bookmarks.id = bookmark_tags.bookmark_id
        JOIN tags ON bookmark_tags.tag_id = tags.id
        WHERE bookmarks.user_id = ? AND tags.name = ?
        ORDER BY bookmarks.created_at DESC
    ");
    $stmt->execute([$user_id, $tag]);
    $bookmarks = $stmt->fetchAll();

}else{
    $stmt = $pdo->prepare("SELECT * FROM bookmarks WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $bookmarks = $stmt->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title></title>
</head>
<body>
    <h1>ブックマーク一覧<h1>
    <form action="index.php" method="GET">
        <input type="text" name="q" placeholder="検索..." value="<?= h($_GET['q'] ?? '') ?>">
        <button type="submit">検索</button>
    </form>
    <a href="./bookmark/add_bookmark.php">新規追加</a>
    <ul>
        <?php foreach($bookmarks as $bookmark): ?>
            <li>
                <a href="<?= h($bookmark->url); ?>" target="_blank">
                    <?= h($bookmark->title); ?>
                </a>
                <p><?= h($bookmark->description);?></p>
                
                <?php 
                    $stmt = $pdo->prepare("
                        SELECT tags.name FROM tags
                        INNER JOIN bookmark_tags ON tags.id = bookmark_tags.tag_id
                        WHERE bookmark_tags.bookmark_id = ?
                    ");
                    $stmt->execute([$bookmark->id]);
                    $tag_names = $stmt->fetchAll(PDO::FETCH_COLUMN);
                ?>
                <small>
                    <?php foreach ($tag_names as $tag_name):?>
                        <a href="index.php?tag=<?=urlencode($tag_name);?>"><?=h($tag_name);?></small>
                    <?php endforeach; ?>
                <a href="./bookmark/edit_bookmark.php?id=<?=$bookmark->id?>"> 編集する </a>
                <a href="./bookmark/delete_bookmark.php?id=<?=$bookmark->id?>"> 削除 </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>