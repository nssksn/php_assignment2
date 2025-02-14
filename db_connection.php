<?php
/*----------------------------------------------------
データベースへの接続
-----------------------------------------------------*/
const DB_HOST = "mysql:host=localhost;dbname=twitter";
const DB_USER = "root";
const DB_PASSWORD = "";

//例外処理
try{
    $pdo = new PDO(DB_HOST, DB_USER, DB_PASSWORD, [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //連想配列
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //例外を表示する
        PDO::ATTR_EMULATE_PREPARES => false, //SQLインジェクション対策
    ]);

}catch(PDOException $e){
    echo "接続失敗", $e->getMessage(). "¥n";
    exit();
}
?>