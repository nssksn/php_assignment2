<?php
/*-----------------------------
post.phpから遷移させる
ログインしているユーザー情報を取得
ユーザー情報を変更し、データベースを変更する
パスワード変更は現在のパスワードが一致していた場合のみ変更可能にする
全てのツイートを見に行けるようにする
ログアウトボタンを追加する
------------------------------*/

session_start();

require "db_connection.php";

//登録情報を更新する
if(!empty($_POST["update"]))
{
    if(!empty($_POST["first_name"]))
    {
        //ファーストネーム変更
        //UPDATE テーブル名 SET カラム名 = 値1
        $sql = "UPDATE users SET first_name = ? WHERE id = ?";
        $new_first_name = $pdo->prepare($sql);
        $new_first_name->execute(array($_POST["first_name"], $_SESSION["id"]));

    }
    if(!empty($_POST["last_name"]))
    {
        //ラストネーム変更
        $sql = "UPDATE users SET last_name = ? WHERE id = ?";
        $new_last_name = $pdo->prepare($sql);
        $new_last_name->execute(array($_POST["last_name"], $_SESSION["id"]));
    }
    if(!empty($_POST["email"]))
    {
        //email変更
        //登録されているemailと同じものでなければ更新
        $sql = "SELECT email FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($_POST["email"]));
        $stmt = $stmt->fetchall();

        if(!isset($stmt[0]["email"])){
            echo "email変更";
            $sql = "UPDATE users SET email = ? WHERE id = ?";
            $new_email = $pdo->prepare($sql);
            $new_email->execute(array($_POST["email"], $_SESSION["id"]));
        }else{
            echo "メールアドレスが重複しています。";
        }

    }
    if(!empty($_POST["password"]) && !empty($_POST["new_password"]))
    {
        //データベースのパスワードを取得
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array($_SESSION["id"]));
        $pass = $stmt->fetchall();

        //POSTされたパスワードをハッシュ化
        $cur_pass = hash("sha256", $_POST["password"]);
        $new_pass = hash("sha256", $_POST["new_password"]);


        //データベースと値が一致しているか確認
        if($cur_pass === $pass[0]["password"]){
            echo "パスワード変更";
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $update_password = $pdo->prepare($sql);
            $update_password->execute(array($new_pass, $_SESSION["id"]));
        }else{
            echo "パスワード変更できません";
        }


    }
}

//ログインしているユーザー情報を取得
if(isset($_SESSION["id"]))
{
    $sql = "SELECT * FROM users WHERE id=?";
    $member = $pdo->prepare($sql);
    $member->execute([$_SESSION["id"]]);
    $member=$member->fetchall();
}
else
{
    echo "ユーザー情報を取得できませんでした。";
}



?>

<?php
    function h($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
    }
    
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <div class="wrapper">
        <header>
            <h1>ユーザー情報</h1>
            
            <div class="header_right">
                <a href="login.php">ログアウト</a>
            
                <form action="post.php", method="post">
                    <input type="submit" name="btn_return" value="全てのツイートを見に行く">
                </form>
            </div>
        </header>
        <div class="user_info">
            <h2>現在の登録情報</h2>
            <dl>
                <dt>ファーストネーム</dt>
                <dd><?php echo h($member[0]["first_name"]) ?></dd>
                <dt>ラストネーム</dt>
                <dd><?php echo h($member[0]["last_name"]) ?></dd>
                <dt>メールアドレス</dt>
                <dd><?php echo h($member[0]["email"]) ?></dd>
                <div class="cf"></div>
            </dl>
        </div>
        <div class="exchange_info">
            <h2>登録情報を変更する</h2>
            <form action="" method="post" class="register_form">
                <div class="register_form">
                    <label for="first_name">ファーストネーム</label>
                    <input type="text" name="first_name" id="first_name">
                </div>
                <div class="register_form">
                    <label for="last_name">ラストネーム</label>
                    <input type="text" name="last_name" id="last_name">
                </div>
                <div class="register_form">
                    <label for="email">メールアドレス</label>
                    <input type="email" name="email" id="email">
                </div>
                <div class="register_form">
                    <label for="password">現在のパスワード</label>
                    <input type="password" name="password" id="password">
                </div>
                <div class="register_form">
                    <label for="new_password">新しいパスワード</label>
                    <input type="password" name="new_password" id="new_password">
                </div>
                <input type="submit" name="update" value="変更">
            </form>
        </div>
    </div>
    </body>
</html>