<?php
/*----------------------------------------------
ログインフォーム、ログイン、登録フォーム。三つに切り替える
clear db_connection.phpを読み込み、データベースを接続させる
clear function.phpを読み込み、ログイン処理を追加する
insert.phpを読み込み、ユーザー登録画面を作成する
-----------------------------------------------*/
session_start();
$pageFlag = 0;

if(!empty($_POST["btn_login"])){
    $pageFlag = 1;
}

if(!empty($_POST["btn_register"])){
    $pageFlag = 2;
}


if(!empty($_POST["register"])){
    $pageFlag = 3;
}

if(!empty($_POST["return"])){
    $pageFlag = 0;
}

?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
   
    <!-- ログイン後 -->
        <?php if($pageFlag === 1) :?>
            <?php  require "function.php"?>
            <?php if(login_func($_POST) === true) :?>
            ログイン中
            <?php else: ?>
                <?php $pageFlag = 0; ?>
            <?php endif;?>
        <?php endif; ?>
    <!-- 会員登録画面-->
    
        <?php if($pageFlag === 3) :?>
            <?php require "insert.php"?>
            <?php if(insertContact($_POST) === true) :?>
                登録完了
                <?php $pageFlag = 0; ?>
            <?php else :?>
                登録できませんでした
                <?php $pageFlag = 2; ?>
            <?php endif ;?>
        <?php endif; ?>
            

        <?php if($pageFlag === 2) :?>
            <div class="wrapper center register">
            <div class="inner_wrapper">
                <h2>会員登録画面</h2>
                <form method="POST" action="login.php" class="register_form">
                    <div class="register_form">
                        <label>ファーストネーム</label>
                        <input type="text" name="first_name" >
                    </div>
                    <div class="register_form">
                        <label>ラストネーム</label>
                        <input type="text" name="last_name">
                    </div>
                    <div class="register_form">
                        <label>メールアドレス</label>
                        <input type="email" name="email">
                    </div>
                    <div class="register_form">
                        <label>パスワード</label>
                        <input type="password" name="password">
                    </div>
                    <input type="submit" name="register" value="会員登録">
                </form>
                <form method = "POST" action="login.php">
                    <input type="submit" name="return" value="戻る">
                </form>
            </div>
            </div>

        <?php endif; ?>

     <!-- ログイン前  -->
     <?php if($pageFlag === 0) :?>
        <div class="wrapper center login">
            <div class="inner_wrapper">
                <h2>ログイン画面</h2>
                <form method="POST" action="login.php" class="login_form">
                    <div class="login_form">            
                    <label for="email">メールアドレス</label>
                    <input type="email" name="email" id="email">
                    </div>
                    <div class="login_form">
                    <label for="password">パスワード</label>
                    <input type="password" name="password" id="password"> 
                    </div>
                    <input type="submit" name="btn_login" value="ログイン">           
                    <br>
                </form>
                <form method = "POST" action="login.php">
                    <input type="submit" name="btn_register" value="新規会員登録">
                </form>
            </div>
         </div>
    <?php endif; ?>

    </body>
</html>