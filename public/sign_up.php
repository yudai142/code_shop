<?php
session_start();
require_once "../dbc/dbc.php";
require_once '../functions/UserLogic.php';

$result = UserLogic::checkLogin();
if($result){
  header('Location: mypage.php');
  return;
}

if (!empty($_POST)) {

  if ($_POST['name'] === '') {
    $error['name'] = 'blank';
  }
  if (strlen($_POST['password']) < 6) {
    $error['password'] = 'length';
  }
  if ($_POST['password'] === '') {
    $error['password'] = 'blank';
  }

}

if (!empty($_POST) && empty($error)) {
  // ユーザー名が重複していないかのチェック
  $user = UserLogic::getUserByUserName($_POST['name']);
  if (!$user) {
    UserLogic::createUser($_POST);
    // $statement = dbc()->prepare('INSERT INTO users SET user_name=?, password=?, created_date=NOW()');
    // $statement->execute(array(
      // $_POST['name'],
      // sha1($_POST['password'])
    // ));
    // unset($_POST);
    $_SESSION['success_message'] = "ユーザーを作成しました";
    header('Location: login.php');
    exit();
  }else{
    $_SESSION['msg'] = '既に使われているユーザー名です';
  }
}

if(isset($_SESSION['message'])){
  $message = $_SESSION['message'];
  unset($_SESSION['message']);
}elseif(isset($_SESSION['success_message'])){
  $success_message = $_SESSION['success_message'];
  unset($_SESSION['success_message']);
}
if(isset($_SESSION['msg'])){
  $err['msg'] = $_SESSION['msg'];
  unset($_SESSION['msg']);
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
      <?php if(isset($err['msg'])): ?>
        <p class="text-danger"><?php echo $err['msg'];?></p>
      <?php endif ?>
      <?php if(isset($message)):?>
        <p class="text-danger"><?php echo $message; ?></p>
      <?php elseif(isset($success_message)): ?>
        <p class="text-primary"><?php echo $success_message; ?></p>
      <?php endif;?>
      <h2>ユーザー登録</h2>
        <form action="" method="post">
          <div class="form-group">
            <label for="exampleInputEmail1">ユーザー名</label>
            <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            <?php if ($error['name'] === 'blank'): ?>
            <p class="error">*ニックネームを入力してください</p>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">パスワード</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1">
            <?php if ($error['password'] === 'blank'): ?>
            <p class="error">*パスワードを入力してください</p>
            <?php endif; ?>
            <?php if ($error['password'] === 'length'): ?>
            <p class="error">*パスワードは6文字以上で入力してください</p>
            <?php endif; ?>
          </div>
          <button type="submit" class="btn btn-primary">登録する</button>
        </form>
    </div>
</body>
</html>