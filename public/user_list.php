<?php 

if($_SESSION["login_user"]['user_name'] !== "admin"){
  $_SESSION['message'] = 'アクセス権限がありません';
  header('Location: item_list.php');
  exit();
}


?>