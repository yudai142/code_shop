<?php 
session_start();
require_once "../dbc/dbc.php";
require_once '../functions/security.php';
require_once '../functions/UserLogic.php';


UserLogic::checkLogin();
$items = getOpenItems();

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
          <img class="rounded" src="../item_image/<?php echo "{$item['image']}" ?>" alt="Card image cap"  style="height:125px;">
        </figure>
        <div class="card-body">
          <h5 class="card-title"><?php echo "{$item['name']}" ?></h5>
          <a href="#" class="btn btn-primary">カートに入れる</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>