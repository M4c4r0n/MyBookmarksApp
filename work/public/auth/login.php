<?php
session_start();
require(__DIR__ . "/../../app/db.php");
$result = "wj";
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
        // echo "ログイン成功！ <a href='../index.php'>ブックマーク一覧へ</a>";
        $result = "ok";
    } else {
        // echo "メールアドレスまたはパスワードが違います";
        $result = "ng";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>My Todos</title>
  <link rel="stylesheet" href="../css/styles_login.css">
</head>
<header>
    <h1>BookMarksApp</h1>
</header>
<body>
    <div class="check"><?=$result?></div>
    <main>
        <h2>ログイン</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="メールアドレス" required>
            <input type="password" name="password" placeholder="パスワード" required>
            <button type="submit">ログイン</button>
        </form>
        <p>または<a href="register.php">新規登録</a></p>
    </main>
    <script src="../js/login.js"></script>
</body>
</html>
