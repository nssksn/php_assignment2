<?php
/*----------------------------------
ログイン処理
1. formの値を取得
2. データベースの値を取得
3. それらの値が一致していればTRUE
-----------------------------------*/
//DB接続　PDO
function login_func($request){
require "db_connection.php";


//フォームの値を変数に代入

$hashed_password = hash("sha256", $request["password"]);

$params = [
    "email" => $request["email"],
    "password" => $hashed_password
];

/*
$params = [
    "first_name" => "John",
    "last_name" => "Doe",
    "email" => "john@john.com"
];
*/
//データベースの値をとってくる

$sql = "SELECT * FROM `users` WHERE email = ?" ;
$stmt = $pdo->prepare($sql);//プレペアステートメント
$stmt->bindValue(1, $params["email"]);

$stmt->execute();//実行
$user = $stmt->fetchall();



//echo $params["email"];



//データベースの値と一致しているか確認
if($params["password"] === $user[0]["password"]){
    echo "ログイン可能";
    $_SESSION["id"] = $user[0]["id"];
    $_SESSION["email"] = $user[0]["email"];
    header("location:post.php");
    return true;
}else{
    echo "ログイン失敗";
    return false;
}

}//functionのカッコ
?>


