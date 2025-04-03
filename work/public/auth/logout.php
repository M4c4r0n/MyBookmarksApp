<?php
session_start();
session_destroy(); // セッションを破棄
header("Location: http://localhost:8080/auth/login.php");
exit;
?>