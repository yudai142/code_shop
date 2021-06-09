<?php
function dbc(){
  $host = "localhost";
  $dbname = "code_shop";
  $user = "root";
  $pass = "root";

  $dns = "mysql:host=$host;dbname=$dbname;charset=utf8;";

  try {
    $pdo = new PDO($dns, $user, $pass, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    // echo 'Mysql接続に成功しました';
    // echo '<br>';
    return $pdo;
  } catch(PDOException $e) {
    exit($e->getMessage());
    echo '<br>';
  }
}

// ファイルデータの取得
// @return array $fileData

function getAllFile(){
  $sql = "SELECT * FROM items";
  $fileData = dbc()->query($sql);
  return $fileData;
}

function getOpenItems(){
  $sql = "SELECT * FROM items WHERE status = 1";
  $fileData = dbc()->query($sql);
  return $fileData;
}

function getAllUser(){
  $sql = "SELECT * FROM users";
  $fileData = dbc()->query($sql);
  return $fileData;
}
// 悪質な入力があった際にエスケープ処理を行う
function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

?>