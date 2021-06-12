<?php
session_start();
require_once "../dbc/dbc.php";
require_once '../functions/ItemLogic.php';
require_once '../functions/UserLogic.php';

if($_SESSION["login_user"]['user_name'] !== "admin"){
  $_SESSION['message'] = 'アクセス権限がありません';
  header('Location: item_list.php');
  exit();
}

UserLogic::checkLogin();

if (!empty($_POST)) {
  if ($name = !filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS)) {
    $err['name'] = '商品名を入力してください';
  }

  if (!$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_SPECIAL_CHARS)) {
    $err['price'] = '値段を入力してください';
  }elseif(!filter_var(filter_var($price))){
    $err['price'] = '値段は半角数字且つ1以上の数値で入力してください';
  }

  if (!$stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_SPECIAL_CHARS)) {
    $err['stock'] = '個数を入力してください';
  }elseif(!filter_var($stock)){
    $err['stock'] = '個数は半角数字且つ1以上の数値で入力してください';
  }

  if (!is_uploaded_file($_FILES['image']['tmp_name'])) {
    $err['image'] = '商品画像を入れてください';
  }else{
    $filename = $_FILES['image']['name'];
    $allow_ext = array('jpg', 'jpeg', 'png', 'gif');
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!in_array(strtolower($file_ext), $allow_ext)) {
      $err['image'] = '写真などは「.gif」または「.jpg」「.png」の画像を指定してください';
    }
  }
  
  if (empty($err)) {
    if($result = ItemLogic::RegisterItems($_POST, $_FILES)){
      $_SESSION['success_message'] = "商品を登録しました";
      header('Location: item_list.php');
      exit();
    }else{
      $_SESSION['message'] = "商品の登録が失敗しました";
    }
  }

}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../read/src_link.php" ?>
    <title>Document</title>
</head>
<body>
    <?php require_once "header.php"; ?>
    <div class="container col-6">
      <h2>商品の登録</h2>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="exampleInputEmail1">商品名：</label>
            <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $_POST["name"]?>">
            <?php if (!empty($err['name'])): ?>
            <p class="error text-danger"><?php echo $err['name']?></p>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">値段：</label>
            <input type="number" name="price" min="1" value="<?php echo $_POST["price"]?>">
            <?php if (!empty($err['price'])): ?>
            <p class="error text-danger"><?php echo $err['price']?></p>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">個数：</label>
            <input type="number" name="stock" min="1" value="<?php echo $_POST["stock"]?>">
            <?php if (!empty($err['stock'])): ?>
            <p class="error text-danger"><?php echo $err['stock']?></p>
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
            <?php if (!empty($err['image'])): ?>
            <p class="error text-danger"><?php echo $err['image']?></p>
            <?php elseif(!empty($err)):?>
            <p class="error text-danger">恐れ入りますが、画像を改めて指定してください</p>
            <?php endif; ?>
          </div>
          <button type="submit" class="btn btn-primary">登録する</button>
        </form>
    </div>
</body>
</html>