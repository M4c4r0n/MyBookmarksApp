
<?php
session_start();
require(__DIR__ . "/../../app/db.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: http://localhost:8080/auth/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION["user_id"];
    $title = $_POST["title"];
    $url = $_POST["url"];
    $description = $_POST["description"];
    $tags = isset($_POST["tags"])? explode(",",$_POST["tags"]):[];

    $stmt = $pdo->prepare("INSERT INTO bookmarks (user_id, title, url, description) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_id, $title, $url, $description])) {
        $bookmark_id = $pdo->lastInsertId();

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
            $stmt->execute([$bookmark_id,$tag_id]);
        }
        header("Location: http://localhost:8080/index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles_add.css">
    <title>addBookMark</title>
</head>
<header>
    <h1>BookMarksApp</h1>
</header>
<body>
    
    <main>
        <h2>ブックマーク新規追加</h2>
        <form action="add_bookmark.php" method="POST">
            <input type="text" name="title" placeholder="タイトル" required>
            <input type="url" name="url" placeholder="URL" required>
            <textarea name="description" placeholder="メモ"></textarea>
            <input type="text" name="tags" placeholder="タグ（カンマ区切り）">
            <button type="submit">追加</button>
        </form>
    </main>
</body>
</html>