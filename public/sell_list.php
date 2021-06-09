<?php
session_start();
require_once "../dbc/dbc.php";

if($_SESSION['name'] !== "admin"){
  $_SESSION['message'] = 'アクセス権限がありません';
  header('Location: index.php');
  exit();
}

$items = getAllFile();

// 商品の削除処理
if ($_REQUEST["sql_kind"] === "delete" && is_numeric($_REQUEST['item_id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_REQUEST['item_id'];
  $statement = dbc()->prepare('DELETE FROM items WHERE id=?');
  $statement->execute(array($id));
  $_SESSION['message'] = '商品を削除しました。';
  header('Location: sell_list.php');
  exit();
}

// 在庫数の更新処理
if ($_REQUEST["sql_kind"] === "update" && is_numeric($_REQUEST['item_id']) && $_SERVER['REQUEST_METHOD'] === 'POST' && is_numeric($_POST['update_stock'])) {
  $statement = dbc()->prepare('UPDATE items SET stock=? WHERE id=?');
  $statement->execute(array($_POST['update_stock'], $_REQUEST['item_id']));
  $_SESSION['success_message'] = '商品の在庫数を更新しました。';
  header('Location: sell_list.php');
  exit();
}

// 公開・非公開の切り替え処理
if ($_REQUEST["sql_kind"] === "change" && is_numeric($_REQUEST['item_id']) && $_SERVER['REQUEST_METHOD'] === 'POST' && is_numeric($_REQUEST['change_status'])) {
  if($_REQUEST['change_status'] == 1){
    $statement = dbc()->prepare('UPDATE items SET status=? WHERE id=?');
    $statement->execute(array(0, $_REQUEST['item_id']));
    $_SESSION['success_message'] = '商品を非公開に設定しました。';
    header('Location: sell_list.php');
    exit();
  }else{
    $statement = dbc()->prepare('UPDATE items SET status=? WHERE id=?');
    $statement->execute(array(1, $_REQUEST['item_id']));
    $_SESSION['success_message'] = '商品を公開に設定しました。';
    header('Location: sell_list.php');
    exit();
  }
}

if(isset($_SESSION['message'])){
  $message = $_SESSION['message'];
  unset($_SESSION['message']);
}elseif(isset($_SESSION['success_message'])){
  $success_message = $_SESSION['success_message'];
  unset($_SESSION['success_message']);
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
    <div class="container col-10">
      <table class="table">
      <thead>
        <tr style="vertical-align: middle;text-align: center;">
          <th scope="col">商品画像</th>
          <th scope="col">商品名</th>
          <th scope="col">価格</th>
          <th scope="col">在庫数</th>
          <th scope="col">ステータス</th>
          <th scope="col">操作</th>
        </tr>
      </thead>
      <tbody>
      <?php if(isset($message)):?>
        <p class="text-danger"><?php echo $message; ?></p>
      <?php elseif(isset($success_message)): ?>
        <p class="text-primary"><?php echo $success_message; ?></p>
      <?php endif;?>
      <?php foreach($items as $item): ?>
        <tr style="vertical-align:middle;text-align:center;<?php if($item['status'] != 1){echo 'background-color: #A9A9A9;';}?>">
          <td><img src="../item_image/<?php echo "{$item['image']}" ?>" alt="" style="height:125px;"></td>
          <td style="vertical-align:middle;"><?php echo "{$item['name']}" ?></td>
          <td style="vertical-align:middle;"><?php echo "{$item['price']}" ?>円</td>
          <td style="vertical-align:middle;">
          <form action="" method="post">
            <input type="text" style="width:60px;text-align:right;" name="update_stock" value="<?php echo "{$item['stock']}" ?>">個&nbsp;&nbsp;<input type="submit" value="変更する" class="btn btn-primary">
            <input type="hidden" name="item_id" value="<?php echo "{$item['id']}" ?>">
            <input type="hidden" name="sql_kind" value="update">
          </form>
          </td>
          <form method="post">
          <td style="vertical-align:middle;">
          <?php if($item['status'] == 1):?>
          <input type="submit" class="btn btn-warning" value="非公開にする">
          <?php else: ?>
          <input type="submit" class="btn btn-success" value="公開する">
          <?php endif ?>
          </td>
          <input type="hidden" name="change_status" value="<?php echo "{$item['status']}" ?>">
          <input type="hidden" name="item_id" value="<?php echo "{$item['id']}" ?>">
          <input type="hidden" name="sql_kind" value="change">
          </form>
          <td style="vertical-align:middle;">
          <form action="" method="post">
            <input type="submit" value="削除する" class="btn btn-danger">
            <input type="hidden" name="item_id" value="<?php echo "{$item['id']}" ?>">
            <input type="hidden" name="sql_kind" value="delete">
          </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
    
</body>
</html>