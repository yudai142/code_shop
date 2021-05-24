<?php
require_once "./dbc.php";
$items = getAllFile();
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
        <?php foreach($items as $item): ?>
        <tr style="vertical-align:middle;text-align:center;<?php if($item['status'] != 1){echo 'background-color: #A9A9A9;';}?>">
          <td><img src="<?php echo "{$item['image']}" ?>" alt="" style="height:125px;"></td>
          <td style="vertical-align:middle;"><?php echo "{$item['name']}" ?></td>
          <td style="vertical-align:middle;"><?php echo "{$item['price']}" ?>円</td>
          <td style="vertical-align:middle;">
          <input type="text" style="width:60px;text-align:right;" value="<?php echo "{$item['stock']}" ?>">個&nbsp;&nbsp;<input type="submit" value="変更する">
          </td>
          <form method="post">
          <td style="vertical-align:middle;">
          <?php if($item['status'] == 1):?>
          <input type="submit" value="非公開にする">
          <?php else: ?>
          <input type="submit" value="公開する">
          <?php endif ?>
          </td>
          <input type="hidden" name="change_status" value="<?php echo "{$item['status']}" ?>">
          <input type="hidden" name="item_id" value="<?php echo "{$item['id']}" ?>">
          <input type="hidden" name="sql_kind" value="change">
          </form>
          <td style="vertical-align:middle;"><button class="btn btn-danger">削除する</button></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
    
</body>
</html>