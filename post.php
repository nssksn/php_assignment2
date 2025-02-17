<?php

/*--------------------------------
login.phpからpost.phpに遷移する
ログインしたユーザーが誰かわかるようにする
投稿できるようにする
全てのユーザーの投稿を見れるようにする
$params["email"]を$_SESSION["email"]に変更する
返信画面を作成する
----------------------------------*/
session_start();

require "db_connection.php";

//ログインしたユーザー情報を取得
if(isset($_SESSION["id"]))
{
    $sql = "SELECT * FROM users WHERE id=?";
    $member = $pdo->prepare($sql);
    $member->execute([$_SESSION["id"]]);
    $member=$member->fetchall();
}
else 
{
    echo "IDが取得できませんでした。";
    header("location:login.php");
}

/*
$params = [
    "email" => "test@test.com"
];

//ログインしたユーザー情報を取得
if(isset($params["email"])){
    $sql = "SELECT * FROM users WHERE email=?";
    $member = $pdo->prepare($sql);
    $member->execute([$params["email"]]);
    $member=$member->fetchall();
}else{
    echo "ユーザー情報を取得できませんでした。";
    //return false;
}
*/

//ログインしたユーザーが投稿した情報をデータベースに登録する
if(!empty($_POST["post"]) && !empty($_POST["tweet"]))
{
    $sql = "INSERT INTO tweets (user_id, tweet)VALUE(" . $member[0]["id"] . ", ?)";
    $my_post = $pdo->prepare($sql);
    $my_post->execute(array($_POST["tweet"]));
}


//ツイータされたデータを取得する
$sql = "SELECT u.first_name, u.last_name, t.* FROM tweets t JOIN users u ON t.user_id = u.id";
$posts = $pdo->query($sql);
$posts = $posts->fetchall();

$posts = array_reverse($posts);

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
        <meta charset = "utf-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="wrapper">
            <header>
                <h1>投稿画面</h1>
                
                <div class="header_right">
                    <a href="login.php">ログアウト</a>
                    <form action="user_info.php", method="post">
                        <input type="submit" name="btn_user_info" value="ユーザー情報を編集する">
                    </form>
                </div>
            </header>

            <div class="hello">
                <div class="inner_wrapper">
                    <P>
                        <?php echo h($member[0]["first_name"]); ?>さん、ようこそ
                    </p>
                
            

                    <form aciton="" method="post">
                        <textarea class="tweet_area" name="tweet"></textarea>
                        <br>
                        <input type="submit" name="post" value="ツイートする">
                    </form>
                </div>
            </div>

            <!--ツイートされた内容-->
            <div class="tweets">
                <h2>ツイートされた内容</h2>

                <?php foreach($posts as $post): ?>
                    <div class="inner_wrapper post">
                        <form action="replys.php" method="post">
                            <dl>
                                <dt>
                                    <?php echo h($post["first_name"]); ?>
                                    <?php echo h($post["last_name"]); ?>
                                </dt>
                                <dd><?php echo h($post["tweet"]); ?></dd>
                            </dl>
                            
                            
                            <input type="hidden" name="user_id" value="<?php echo h($post["user_id"]) ?>">
                            <input class="btn btn_reply" type="submit" name="reply" value="返信する">
                            <div class="cf"></div>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </body>
</html>