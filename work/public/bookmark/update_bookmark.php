<?php
session_start();
require(__DIR__ . "/../../app/db.php");

if (!isset($_SESSION["user_id"])) {
    echo "ログインしてください";
    echo "<a href='../auth/login.php'>ログイン画面へ</a>";
    exit;
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $user_id = $_SESSION["user_id"];
    $title = $_POST["title"];
    $url = $_POST["url"];
    $description = $_POST["description"];
    $tags = isset($_POST["tags"])?explode(",",$_POST["tags"]):[];

    $stmt = $pdo->prepare("UPDATE bookmarks SET title = ?, url = ?, description = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $url, $description, $id, $user_id]);

    $stmt = $pdo->prepare("DELETE FROM bookmark_tags WHERE bookmark_id = ?");
    $stmt->execute([$id]);

    foreach($tags as $tag_name){
        $tag_name = trim($tag_name);
        
        $stmt = $pdo->prepare("SELECT id FROM tags WHERE name = ?");
        $stmt->execute([$tag_name]);
        $tag = $stmt->fetch();

        if($tag){
            $tag_id = $tag->id;
        }
        else{
            $stmt = $pdo->prepare("INSERT INTO tags (name) VALUES (?)");
            $stmt->execute([$tag_name]);
            $tag_id = $pdo->lastInsertId();
        }
        $stmt = $pdo->prepare("INSERT INTO bookmark_tags (bookmark_id,tag_id) VALUES (?, ?)");
        $stmt->execute([$id,$tag_id]);
        
    }
}



?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="../index.php">更新しました！一覧へ</a>
</body>
</html>