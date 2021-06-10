<?php
session_start();
require_once "../dbc/dbc.php";
require_once '../functions/security.php';
require_once '../functions/UserLogic.php';

$result = UserLogic::checkLogin();
if($result){
  header('Location: mypage.php');
  return;
}

if (!empty($_POST)) {
  if(!$username = filter_input(INPUT_POST, 'name')) {
    $error['name'] = 'ユーザー名を入力してください';
  }elseif(!preg_match("/\A[a-z\d]{6,100}+\z/i",$username)){
    $error['name'] = 'ユーザー名は6文字以上100文字以下で、半角英数字で入力してください。';
  }

  if(!$password = filter_input(INPUT_POST, 'password')) {
    $error['password'] = 'パスワードを入力してください';
  }elseif(!preg_match("/\A[a-z\d]{6,100}+\z/i",$password)){
    $error['password'] = 'パスワードは6文字以上100文字以下で、半角英数字で入力してください。';
  }
  // パスワードの確認と一致しているかの判定
  if(!$password_conf = filter_input(INPUT_POST, 'password_conf')){
    $error['password_conf'] = 'パスワードを再度入力してください';
  }elseif($password !== $password_conf) {
    $error['password_conf'] = '確認用パスワードと異なっています';
  }
}

if (!empty($_POST) && empty($error)) {
  // CSRF対策処理
  $token = filter_input(INPUT_POST, 'csrf_token');
  // トークンがない、もしくは一致しない場合、処理を中止
  if(!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']){
    exit('不正なリクエスト');
  }
  unset($_SESSION['csrf_token']);
  // ユーザー名が重複していないかのチェック
  $user = UserLogic::getUserByUserName($username);
  if (!$user) {
    $hasCreated = UserLogic::createUser($_POST);
    if($hasCreated) {
      $_SESSION['success_message'] = "ユーザーを作成しました";
      header('Location: login.php');
      exit();
    }else{
      $_SESSION['message'] = "登録に失敗しました";
    }
  }else{
    $error['name'] = '既に使われているユーザー名は登録できません';
  }
}

if(!isset($_SESSION['csrf_token'])){
  unset($_SESSION['csrf_token']);
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
    <div class="container col-6">
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
            <?php if (isset($error['name'])): ?>
              <p class="error text-danger"><?php echo $error['name'] ?></p>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">パスワード</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1">
            <?php if (isset($error['password'])): ?>
              <p class="error text-danger"><?php echo $error['password'] ?></p>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">パスワード確認</label>
            <input type="password" name="password_conf" class="form-control" id="exampleInputPassword1">
            <?php if (isset($error['password_conf'])): ?>
              <p class="error text-danger"><?php echo $error['password_conf'] ?></p>
            <?php endif; ?>
          </div>
          <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
          <button type="submit" class="btn btn-primary">登録する</button>
        </form>
    </div>
</body>
</html>