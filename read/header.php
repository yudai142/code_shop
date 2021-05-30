<?php
session_start();
?>
<header>
<nav class="navbar navbar-light bg-primary mb-5 p-0">
    <div class="container">
    <div class="navbar-left">
        <a class="navbar-brand" href="./index.php">
        <img src="./images/logo.png" alt="CodeSHOP">
        </a>
    </div>
    <div class="navbar-right">
      <?php if (isset($_SESSION['id'])) : ?>
        <span class="text-white font-weight-bold">ユーザー名：<?php echo $_SESSION['name']?></span>
        <a href="./cart.php" class="font-weight-bold text-info" data-toggle="tooltip" title="カート一覧" data-placement="bottom">
          <img src="./images/cart.png" alt="">
        </a>
        <?php if($_SESSION['name'] === "admin"):?>
          <a href="./user_list.php" class="btn btn-success">ユーザー管理ページ</a>
          <a href="./sell_list.php" class="btn btn-warning">商品管理ページ</a>
          <a href="./new_item.php" class="btn btn-info">商品登録</a>
        <?php endif;?>
        <a href="./logout.php" type="button" class="btn btn-danger">ログアウト</a>
      <?php else: ?>
        <a type="button" href="./sign_up.php" class="btn btn-primary">新規登録</a>
        <a type="button" href="./login.php" class="btn btn-primary">ログイン</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
</header>