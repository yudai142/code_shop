<?php
require_once '../dbc/dbc.php';
class ItemLogic {
  // ファイルデータの取得
  // @return array $fileData

  public static function getAllFile(){
    $sql = "SELECT * FROM items";
    $fileData = dbc()->query($sql);
    return $fileData;
  }

  public static function getOpenItems(){
    $sql = "SELECT * FROM items WHERE status = 1";
    $fileData = dbc()->query($sql);
    return $fileData;
  }

  function RegisterItems($itemData, $itemFile){
    $result = False;
    $image = date('YmdHis') . basename($itemFile['image']['name']);
    $upload_dir = '../item_image/';
    $imageFile = $upload_dir . $image;
    move_uploaded_file($itemFile['image']['tmp_name'], $imageFile);
    $sql = "INSERT INTO items (name, price, image, status, stock) VALUE (?, ?, ?, ?, ?)";
  
    try{
      $stmt = dbc()->prepare($sql);
      $stmt->bindValue(1, $itemData['name']);
      $stmt->bindValue(2, (int)$itemData['price']);
      $stmt->bindValue(3, $imageFile);
      $stmt->bindValue(4, (int)$itemData['status']);
      $stmt->bindValue(5, (int)$itemData['stock']);
      $result = $stmt->execute();
      return $result;
    }catch(\Exception $e){
      $e->getMessage();
      return $result;
    }
  }

  public static function EditItems($request){

    // 在庫数の更新処理
    if ($request["sql_kind"] === "update" && is_numeric($request['item_id'] & $request['update_stock'])) {
      $statement = dbc()->prepare('UPDATE items SET stock=? WHERE id=?');
      if($statement->execute(array($request['update_stock'], $request['item_id']))){
        $_SESSION['success_message'] = '商品の在庫数を更新しました。';
        header('Location: sell_list.php');
        exit();
      }else{
        $_SESSION['message'] = '商品の在庫数変更処理が失敗しました。';
        header('Location: sell_list.php');
        exit();
      }
    }

    // 公開・非公開の切り替え処理
    if ($request["sql_kind"] === "change" && is_numeric($request['item_id'] & $request['change_status'])) {
      if($request['change_status'] == 1){
        $statement = dbc()->prepare('UPDATE items SET status=? WHERE id=?');
        if($statement->execute(array(0, $request['item_id']))){
          $_SESSION['success_message'] = '商品を非公開に設定しました。';
          header('Location: sell_list.php');
          exit();
        }else{
          $_SESSION['message'] = '商品の非公開処理が失敗しました。';
          header('Location: sell_list.php');
          exit();
        }
      }else{
        $statement = dbc()->prepare('UPDATE items SET status=? WHERE id=?');
        if($statement->execute(array(1, $request['item_id']))){
          $_SESSION['success_message'] = '商品を公開に設定しました。';
          header('Location: sell_list.php');
          exit();
        }else{
          $_SESSION['message'] = '商品の公開処理が失敗しました。';
          header('Location: sell_list.php');
          exit();
        }
      }
    }

    // 商品の削除処理
    if ($request["sql_kind"] === "delete" && is_numeric($request['item_id'])) {
      $id = $request['item_id'];
      $statement = dbc()->prepare('DELETE FROM items WHERE id=?');
      if($statement->execute(array($id))){
        $_SESSION['message'] = '商品を削除しました。';
        header('Location: sell_list.php');
        exit();
      }else{
        $_SESSION['message'] = '商品の削除処理が失敗しました。';
        header('Location: sell_list.php');
        exit();
      }
    }
    exit('不正なリクエストです');;
  }
}

?>