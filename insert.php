<?php
/*-----------------------------------
SQL文を実行し、入力したデータをSQLに保存
db_connection.phpを読み込み、sql文を実行
パスワードをSHA256に変更する
このパスワードでログインできるか試す
------------------------------------*/

function insertContact($request){

//DB接続
require "db_connection.php";

//入力 DB保存
$password = $request["password"];
$hashed_string = hash("sha256", $password);

$params = [
    "id" => null,
    "first_name" => $request["first_name"],
    "last_name" => $request["last_name"],
    "email" => $request["email"],
    "password" => $hashed_string,
];

/*
$params = [
    "id" => null,
    "first_name" => "なまえ",
    "last_name" => "だれえ",
    "email" => "test@test.com",
    "password" => $hashed_string,
];
*/

//データが入力されていなければfalse
if(empty($params["first_name"])){
    echo "ファーストネームが入力されていません";
}

if(empty($params["last_name"])){
    echo "ラストネームが入力されていません";
}

if(empty($params["email"])){
    echo "emailが入力されていません";
}

if(empty($request["password"])){
    echo "passwordが入力されていません";
}

if(empty($params["first_name"]) || empty($params["last_name"]) || empty($params["email"]) || empty($request["password"])){
    return false;
}


//データベース内のメールアドレスを取得
$sql = "SELECT email FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$params["email"]]);
$row = $stmt->fetchall();



//データベース内のメールアドレスと重複がない確認
if(!isset($row[0]["email"])){
    echo "登録完了";
    //データを登録する

    $count = 0;
    $columns = "";
    $values = "";

    foreach(array_keys($params) as $key){
        if($count++ > 0){
            $columns .= ",";
            $values .= ",";
        }
        $columns .= $key;
        $values .= ":" .$key;
    }

    $sql = "INSERT INTO users (". $columns .")VALUES(". $values .")";

    $stmt = $pdo->prepare($sql);//プレペアステートメント
    $stmt->execute($params); //実行
    return true;
}else{
    echo "メールアドレスを登録できません";
    return false;
}

}//insertContact

?>