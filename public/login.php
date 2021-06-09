<?php 
session_start();
require_once '../dbc/dbc.php'; 
require_once '../functions/UserLogic.php';

// ログインしているか判定し、していれば商品一覧ページに移動
$result = UserLogic::checkLogin();
if($result){
  header('Location: item_list.php');
  return;
}

if(!empty($_POST)){
  // バリデーション(ユーザー名、アドレスが記入されているかの判定)
  if(!$user_name = filter_input(INPUT_POST, 'name')) {
    $err['name'] = 'ユーザー名を記入してください';
  }

  if(!$password = filter_input(INPUT_POST, 'password')){
    $err['password'] = "パスワードを記入してください";
  }
  // エラーメッセージ


  if($_POST['name'] !== '' && $_POST['password'] !== '') {
    $result = UserLogic::login($user_name, $password);
    if($result) {
      if($_POST['save'] === 'on') {
        setcookie('name', $_POST['name'], time()+60*60*24*14);
      }
      // ログイン後の処理
      $_SESSION['success_message'] = "ログインしました";
      header("Location: item_list.php");
      exit;
    }
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

if($_COOKIE['name'] !== '') {
  $name = $_COOKIE['name'];
}
// 
// if(!empty($_POST)) {
  // $name = $_POST['name'];
  // if($_POST['name'] !== '' && $_POST['password'] !== '') {
    // $login = dbc()->prepare('SELECT * FROM users WHERE user_name=? AND password=?');
    // $login->execute(array($_POST['name'], sha1($_POST['password'])));
    // $member = $login->fetch();
    // if($member) {
      // $_SESSION['id'] = $member['id'];
      // $_SESSION['name'] = $member['user_name'];
      // $_SESSION['time'] = time();
      // if($_POST['save'] === 'on') {
        // setcookie('name', $_POST['name'], time()+60*60*24*14);
      // }
      // $_SESSION['success_message'] = "ログインしました";
      // header("Location: item_list.php");
      // exit;
    // }else{
      // $error['login'] = 'failed';
    // }
  // }else{
    // $error['login'] = 'blank';
  // }
// }


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
      <h2>ユーザーログイン</h2>
      <form action="" method="post">
        <div class="form-group">
          <label for="exampleInputEmail1">ユーザー名</label>
          <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="EmailHelp" value="<?php print htmlspecialchars($name, ENT_QUOTES); ?>">
          <?php if(isset($err['name'])): ?>
            <p><?php echo $err['name'];?></p>
          <?php endif ?>
          <!-- <?php if($error['login'] === 'blank'): ?> -->
            <!-- <p class="error">* ユーザー名とパスワードをご記入ください</p> -->
          <!-- <?php endif; ?> -->
          <!-- <?php if($error['login'] === 'failed'): ?> -->
            <!-- <p class="error">* ログインに失敗しました。正しく入力してください</p> -->
          <!-- <?php endif; ?> -->
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">パスワード</label>
          <input type="password" name="password" class="form-control" id="exampleInputPassword1" value="<?php print htmlspecialchars($_POST['password'], ENT_QUOTES); ?>">
          <?php if(isset($err['password'])): ?>
            <p><?php echo $err['password'];?></p>
          <?php endif ?>
        </div>
        <div class="form-group form-check">
          <input type="checkbox" name="save" class="form-check-input" id="exampleCheck1" value="on">
          <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary">ログイン</button>
      </form>
    </div>
    
</body>
</html>