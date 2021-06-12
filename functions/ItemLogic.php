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
    move_uploaded_file($itemFile['image']['tmp_name'], $upload_dir . $image);
    $sql = "INSERT INTO items (name, price, image, status, stock) VALUE (?, ?, ?, ?, ?)";
  
    try{
      $stmt = dbc()->prepare($sql);
      $stmt->bindValue(1,$itemData['name']);
      $stmt->bindValue(2,(int)$itemData['price']);
      $stmt->bindValue(3,$image);
      $stmt->bindValue(4,(int)$itemData['status']);
      $stmt->bindValue(5,(int)$itemData['stock']);
      $result = $stmt->execute();
      return $result;
    }catch(\Exception $e){
      $e->getMessage();
      return $result;
    }
  }
}

?>