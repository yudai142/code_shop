<?php
require_once '../dbc/dbc.php';


class UserLogic {
  /**
   * ユーザーを登録する
   * @param array $userData
   * @return bool(成否判定) $result
   */
  public static function createUser($userData) {
    $result = false;
    $sql = 'INSERT INTO users (user_name, password) VALUES (?, ?)';

    // ユーザデータを配列に入れる
    $arr = [];
    $arr[] = $userData['name'];
    $arr[] = password_hash($userData['password'], PASSWORD_DEFAULT);
    try {
      $stmt = dbc()->prepare($sql);
      $result = $stmt->execute($arr);
      return $result;
    }catch(Exception $e) {
      return $result;
    }
  }

  /**
   * ログイン処理
   * @param array $user_name
   * @param array $password
   * @return bool $result
   */
  public static function login($user_name, $password){
    $result = false;
    // ユーザー名を検索して取得
    $user = self::getUserByUserName($user_name);

    if (!$user) {
      $_SESSION['msg'] = 'ユーザー名が一致しません。';
      return $result;
    }

    // パスワードの照会
    if (password_verify($password, $user['password'])){
      //ログイン成功
      //セッションハイジャック対策(ID再生成)
      session_regenerate_id(true);
      $_SESSION['login_user']["id"] = $user['id'];
      $_SESSION['login_user']["user_name"] = $user['user_name'];
      $result = true;
      return $result;
    }

    $_SESSION['msg'] = 'パスワードが一致しません。';
    return $result;
  }

  /**
   * ユーザー名からユーザーを取得
   * @param string $user_name
   * @return array|bool $user|false
   */
  public static function getUserByUserName($user_name){
    // SQLの準備
    // SQLの実行
    // SQLの結果を返す
    $sql = 'SELECT * FROM users WHERE user_name = ?';

    // ユーザデータを配列に入れる
    $arr = [];
    $arr[] = $user_name;

    try {
      $stmt = dbc()->prepare($sql);
      $stmt->execute($arr);
      // SQLの結果を返す
      $user = $stmt->fetch();
      return $user;
    }catch(Exception $e) {
      return false;
    }
  }


  /**
   * ログインチェック
   * @param void
   * @return bool $result
   */
  public static function checkLogin(){
    $result = false;
    // セッションにログインユーザーが入っていなかったらfalse
    if (isset($_SESSION['login_user']) && $_SESSION['login_user']['id'] > 0) {
      return $result = true;
    }
    return $result;
  }


  /**
   * ログアウト処理
   */
  public static function logout(){
    $_SESSION = array();
    session_destroy();
  }
}