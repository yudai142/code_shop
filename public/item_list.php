<?php 
session_start();
require_once "../dbc/dbc.php";
require_once '../functions/security.php';
require_once '../functions/UserLogic.php';
require_once '../functions/ItemLogic.php';

UserLogic::checkLogin();
$items = ItemLogic::getOpenItems();

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
  <link rel="stylesheet" href="css/common.css">
  <?php require_once "../read/src_link.php" ?>
  <title>Document</title>
</head>
<body>
<?php require_once "header.php"; ?>
<div class="container col-7 pt-5">
  <?php if(isset($message)):?>
    <p class="text-danger"><?php echo $message; ?></p>
  <?php elseif(isset($success_message)): ?>
    <p class="text-primary"><?php echo $success_message; ?></p>
  <?php endif;?>
  <div class="row">
    <?php foreach($items as $item): ?>
    <div class="col-4">
      <div class="card mb-4 shadow-sm">
        <figure class="figure mx-auto pt-2 pb-2 mb-0">
          <img class="rounded" src="<?php echo h($item['image']) ?>" alt="Card image cap"  style="height:125px;">
        </figure>
        <div class="card-body">
          <h5 class="card-title"><?php echo h(mb_strimwidth(strip_tags($item['name']), 0, 24, '…', 'UTF-8' )) ?></h5>
          <p class="fw-bolder"><?php echo h($item['price']) ?>円</p>
          <?php if(filter_var($item['stock'])) : ?>
          <a href="" class="btn btn-primary">カートに入れる</a>
          <?php else :?>
          <span class="btn btn-danger disabled">売り切れ</span>
          <?php endif ;?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>