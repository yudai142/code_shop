<?php
  session_start();
  // セッションを消す
  $_SESSION = array();
  session_destroy();
	header('Location: index.php');
	exit();
?>