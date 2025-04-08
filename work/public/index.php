<?php

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: http://localhost:8080/auth/login.php");
    exit;
}
require(__DIR__ . "/../app/functions.php");
require(__DIR__ . "/../app/db.php");


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
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    
    <header>
        <h1>BookMarksApp</h1>
        <p class="user">ようこそ、<?=$_SESSION["username"]?> </p>
        <p class="logout"><a href='./auth/logout.php'>ログアウト</a></p>
    </header>
    <main>
        <h2>ブックマーク一覧</h2>
        <div class="add">

            <a href="./bookmark/add_bookmark.php">新規作成</a>
            <form class="search" action="index.php" method="GET">
                <input type="text" name="q" placeholder="検索..." value="<?= h($_GET['q'] ?? '') ?>">
                <button type="submit"><img src="img/search.png" width="40px" height="40px"></button>
            </form> 
        </div>
        
        <ul class="contents">
            <?php foreach($bookmarks as $bookmark): ?>
                <li class="content">
                    <a id="title" href="<?= h($bookmark->url); ?>" target="_blank">
                        <?= h($bookmark->title); ?>
                    </a>
                    <p id="description"><?= h($bookmark->description);?></p>
                    
                    <?php 
                        $stmt = $pdo->prepare("
                            SELECT tags.name FROM tags
                            INNER JOIN bookmark_tags ON tags.id = bookmark_tags.tag_id
                            WHERE bookmark_tags.bookmark_id = ?
                        ");
                        $stmt->execute([$bookmark->id]);
                        $tag_names = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    ?>
                    <small id="tags">
                        <?php foreach ($tag_names as $tag_name):?>
                            <a id="tag" href="index.php?tag=<?=urlencode($tag_name);?>"><?=h($tag_name);?></a>
                        <?php endforeach; ?>
                    </small>
                    <div class="edit_delete">
                        <a id="edit" href="./bookmark/edit_bookmark.php?id=<?=$bookmark->id?>"> 編集する </a>
                        <a id="delete" href="./bookmark/delete_bookmark.php?id=<?=$bookmark->id?>"> 削除 </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</body>
</html>