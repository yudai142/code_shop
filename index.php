<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/common.css">
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
  <?php 
  require_once "./dbc.php";
  dbc();
  ?>

</body>
</html>