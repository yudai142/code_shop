<?php 
session_start();
require_once "./dbc.php";

$items = getOpenItems();
if(isset($_SESSION['message'])){
  $message = $_SESSION['message'];
  $_SESSION['message'] = NULL;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/common.css">
  <?php require_once "./read/src_link.php" ?>
  <title>Document</title>
</head>
<body>
<?php require_once "./read/header.php"; ?>
<div class="container col-7 pt-5">
  <?php if(isset($message)):?>
    <?php echo $message; ?>
  <?php endif;?>
  <div class="row">
    <?php foreach($items as $item): ?>
    <div class="col-4">
      <div class="card mb-4 shadow-sm">
        <figure class="figure mx-auto pt-2 pb-2 mb-0">
          <img class="rounded" src="item_image/<?php echo "{$item['image']}" ?>" alt="Card image cap"  style="height:125px;">
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