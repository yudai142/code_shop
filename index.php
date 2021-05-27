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
  <link rel="stylesheet" href="css/common.css">
  <?php require_once "./read/src_link.php" ?>
  <title>Document</title>
</head>
<body>
<header>
  <div class="header-box">
    <a href="./top.php">
      <img class="logo" src="./images/logo.png" alt="CodeSHOP">
    </a>
    <a href="./cart.php" class="cart"></a>
  </div>
</header>
<div class="container col-7 pt-5">
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