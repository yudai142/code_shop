<?php
  session_start();
  require_once "../functions/UserLogic.php";

  /**
   * cookieの削除処理
   */
  // if (ini_get("session.use_cookies")) {
      // $params = session_get_cookie_params();
      // setcookie(session_name(), '', time() - 42000,
          // $params["path"], $params["domain"],
          // $params["secure"], $params["httponly"]
      // );
  // }
  
  if(!$logout = filter_input(INPUT_POST, 'logout')){
    exit('不正なリクエストです');
  }

  // ログインしているか判定し、セッションが切れていたらログインしてくださいとメッセージを出す。
  if (!$result = UserLogic::checkLogin()) {
    exit('セッションが切れましたので、ログインし直してください');
    return;
  }

  // ログアウトする
  UserLogic::logout();
  session_start();
  $_SESSION['message'] = "ログアウトしました";
	header('Location: item_list.php');
	exit();
?>