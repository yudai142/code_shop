<?php 

if($_SESSION['name'] !== "admin"){
  $_SESSION['message'] = 'アクセス権限がありません';
  header('Location: ./index.php');
  exit();
}


?>