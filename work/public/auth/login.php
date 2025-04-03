<?php
session_start();
require(__DIR__ . "/../../app/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    // var_dump($user);
    if ($user && password_verify($password, $user->password)) {
        $_SESSION["user_id"] = $user->id;
        $_SESSION["username"] = $user->username;
        echo "ログイン成功！ <a href='../index.php'>ブックマーク一覧へ</a>";
    } else {
        echo "メールアドレスまたはパスワードが違います";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>My Todos</title>
</head>
<body>
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="メールアドレス" required>
        <input type="password" name="password" placeholder="パスワード" required>
        <button type="submit">ログイン</button>
    </form>
    または<a href="register.php">新規登録</a>
</body>
</html>
