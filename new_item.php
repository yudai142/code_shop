<?php
session_start();
require_once('dbc.php');

if($_SESSION['name'] !== "admin"){
  $_SESSION['message'] = 'アクセス権限がありません';
  header('Location: ./index.php');
  exit();
}

if (!empty($_POST)) {
  if ($_POST['name'] === '') {
    $error['name'] = 'blank';
  }

  if ($_POST['price'] === '') {
    $error['price'] = 'blank';
  }

  if ($_POST['stock'] === '') {
    $error['stock'] = 'blank';
  }

  if ($_FILES['image']['name'] === '') {
    $error['image'] = 'blank';
  }else{
    $filename = $_FILES['image']['name'];
    $allow_ext = array('jpg', 'jpeg', 'png', 'gif');
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!in_array(strtolower($file_ext), $allow_ext)) {
      $error['image'] = 'type';
    }
  }
  

  if (!empty($_POST) && empty($error)) {
    $image = date('YmdHis') . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], './item_image/' . $image);
    $statement = dbc()->prepare('INSERT INTO items SET name=?, price=?, image=?, status=?, stock=?, user_id=?');
    $statement->execute(array(
      $_POST['name'],
      (int)$_POST['price'],
      $image,
      (int)$_POST['status'],
      (int)$_POST['stock'],
      (int)$_SESSION['id']
    ));
    $_SESSION['message'] = "商品を登録しました";
    header('Location: ./index.php');
    exit();
  }

}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "./read/src_link.php" ?>
    <title>Document</title>
</head>
<body>
    <?php require_once "./read/header.php"; ?>
    <div class="container col-6">
      <h2>商品の登録</h2>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="exampleInputEmail1">商品名：</label>
            <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <?php if ($error['name'] === 'blank'): ?>
            <p class="error">*商品名を入力してください</p>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">値段：</label>
            <input type="number" name="price" min="1">
            <?php if ($error['price'] === 'blank'): ?>
            <p class="error">値段を入力してください</p>
            <?php elseif($error['price'] === 'int'):?>
              <p class="error">*値段は数字で入力してください</p>
            <?php elseif($error['price'] === 'more'):?>
              <p class="error">*値段は1以上の数値で入力してください</p>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">個数：</label>
            <input type="number" name="stock" min="1">
            <?php if ($error['stock'] === 'blank'): ?>
            <p class="error">*個数を入力してください</p>
            <?php elseif($error['stock'] === 'int'):?>
              <p class="error">*個数は数字で入力してください</p>
            <?php elseif($error['stock'] === 'more'):?>
              <p class="error">*個数は1以上の数値で入力してください</p>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">ステータス：</label>
            <div class="form-check-inline">
              <input class="form-check-input" type="radio" name="status" id="status1" value="1" checked>
              <label class="form-check-label" for="status1">公開</label>
            </div>
            <div class="form-check-inline">
              <input class="form-check-input" type="radio" name="status" id="status2" value="0">
              <label class="form-check-label" for="status2">非公開</label>
            </div>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">商品画像：</label>
            <input type="file" name="image" size="35" value="test"  />
            <?php if ($error['image'] === 'blank'): ?>
            <p class="error">*商品画像を入れてください</p>
            <?php elseif($error['image'] === 'type'):?>
            <p class="error">*写真などは「.gif」または「.jpg」「.png」の画像を指定してください</p>
            <?php elseif(!empty($error)):?>
            <p class="error">*恐れ入りますが、画像を改めて指定してください</p>
            <?php endif; ?>
          </div>
          <button type="submit" class="btn btn-primary">登録する</button>
        </form>
    </div>
</body>
</html>