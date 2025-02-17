<?php
/*----------------------------------
返信された内容を読み込み
返信内容を送信できるようにする
投稿画面に戻る機能を追加
-----------------------------------*/ 

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
    header("location:post.php");
}


//ツイートを投稿したユーザ情報を取得
if(!empty($_POST["user_id"]))
{
    $sql = "SELECT u.first_name, u.last_name ,t.* FROM tweets t JOIN users u ON t.user_id = u.id WHERE user_id=?";
    $tweet_user = $pdo->prepare($sql);
    $tweet_user->execute([$_POST["user_id"]]);
    $tweet_user=$tweet_user->fetchall();
}
else
{
    echo "ツイートしたユーザーが誰かわかりません";
    header("location:post.php");
}


//返信内容をデータベースに登録
if(!empty($_POST["btn_reply"]) && !empty($_POST["reply"]))
{
    $sql = "INSERT INTO replys (tweet_id, user_id, reply)VALUE(?, ?, ?)";
    $my_reply = $pdo->prepare($sql);
    $my_reply->execute(array($tweet_user[0]["id"], $_SESSION["id"], $_POST["reply"]));
    echo "返信しました。";
}


//返信されている全ての内容を取得
if(!empty($tweet_user[0]["id"]))
{
    $sql = "SELECT r.*, u.first_name, u.last_name FROM replys r JOIN tweets t ON r.tweet_id = t.id JOIN users u ON r.user_id = u.id WHERE t.id = ?";
    $replys = $pdo->prepare($sql);
    $replys->execute([$tweet_user[0]["id"]]);
    $replys = $replys->fetchall();


    $replys = array_reverse($replys);
}else{
    echo "データが入っていません";
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
        <meta charset = "utf-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="wrapper">
            <header>
                <h1>返信画面</h1>

                <div class="header_right">
                    <a href="login.php">ログアウト</a>
                    <form action="post.php", method="post">
                        <input type="submit" name="btn_return" value="全てのツイートを見に行く">
                    </form>
                </div>                
            </header>

            <div class="hello">
                <h2>ツイート内容</h2>
                <div class="inner_wrapper">
                    <div class="anypost">
                        <dl>
                            <dt>
                                <?php echo h($tweet_user[0]["first_name"]); ?>
                                <?php echo h($tweet_user[0]["last_name"]); ?>
                            </dt>
                            <dd><?php echo h($tweet_user[0]["tweet"]); ?></dd>
                        </dl>
                        <div class="cf"></div>
                    </div>
                    <div >
                        <form action="" method="post">
                            <textarea class="tweet_area" name="reply"></textarea>
                            <br>
                            <input type="hidden" name="user_id" value="<?php echo h($tweet_user[0]["user_id"])?>">
                            <input type="submit" name="btn_reply" value="返信する">
                        </form>
                    </div>
                </div>
            </div>


            <div class="tweets">
                <h2>返信内容</h2>
    
                    <?php foreach($replys as $reply): ?>

                        <div class="inner_wrapper post">
                            <dl>
                                <dt>
                                    <?php echo h($reply["first_name"]); ?>
                                    <?php echo h($reply["last_name"]); ?>
                                </dt>
                                <dd><?php echo h($reply["reply"]) ?></dd>
                            </dl>
                            <div class="cf"></div>
                        </div>
                    <?php endforeach;?>
               
            </div>

        </div>
    </body>
</html>