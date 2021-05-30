<?php
  session_start();
  // セッションを消す
  $_SESSION = array();
  $_SESSION['message'] = "ログアウトしました";
	header('Location: index.php');
	exit();
?>