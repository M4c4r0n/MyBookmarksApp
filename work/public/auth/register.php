<?php

require(__DIR__ . "/../../app/db.php");
$result = "ng";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $password])) {
        $result = "ok";
    } else {
        $result = "ng";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles_register.css">
    <title>Document</title>
</head>
<header>
    <h1>BookMarksApp</h1>
</header>
<body>
    <div class="check"><?=$result?></div>
    <main>
        <h2>新規登録</h2>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="ユーザー名" required>
            <input type="email" name="email" placeholder="メールアドレス" required>
            <input type="password" name="password" placeholder="パスワード" required>
            <button type="submit">登録</button>
        </form>
    </main>
    <script src="../js/register.js"></script>
</body>
</html>
