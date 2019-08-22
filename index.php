<?PHP
require ('include/password.php');
session_start();
// errormassegeリセット
$errormessage ="";
// if ログインボタン押されたらスタート
if(isset($_POST["login"])){
    // if useridが未入力の場合の処理
    if(empty($_POST["userid"])){
        $errormessage = 'IDが未入力です';
    //if passが未入力だった場合の処理 
    }elseif(empty($_POST["password"])){
        $errormessage = 'パスワードが未入力です';
    }
    // どちらも入力済みだった場合以下実行
    if(!empty($_POST["userid"]) && !empty($_POST["password"])){
        $userid = $_POST["userid"];
        try{
            // DB接続
            $pdo = new PDO('mysql:dbname=kadai03_db;charset=utf8;host=localhost','root','');
            // SQL文をセット
            $stmt = $pdo->prepare("SELECT * FROM id_table WHERE id = $userid");
            // SQL文を実行
            $stmt->execute();
            // フォームのパスワードを変数に格納
            $password = $_POST["password"];
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (password_verify($password, $row['password'])) {
                    session_regenerate_id(true);
                    $_SESSION["id"] = $row['id'];
                    $_SESSION["name"] = $row['name'];
                    $_SESSION["busho"] = $row['busho'];
                    $_SESSION["shift"] = $row['shift'];
                    $_SESSION["password"] = $password;
                    header("Location: input.php");  // input画面へ遷移
                    exit();  // 処理終了
                } else {
                    // 認証失敗
                    $errormessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
                }
            } else {
                // 該当データなし
                $errormessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
            }
        }catch (PDOException $e) {
         exit('データベースに接続できませんでした。'.$e->getMessage());
        }
    }
}
include('include/head.php');
?>
    <title>ログイン画面</title>
</head>
<body>
    <h1>Portal system</h1>
        <div class="dummy"></div>
    <h2>ログイン画面</h2>
        <form id="loginForm" name="loginForm" action="" method="POST">
            <div class="field">
                <fieldset>
                    <legend>ログインフォーム</legend>
                    <?php
                    echo '<P class="error">'.$errormessage.'</P>';
                    ?>
                    <table>
                        <tr>
                        <th><label for="userid">社員番号　:</label></th>
                        <td><input type="text" id="userid" name="userid" placeholder="ユーザーIDを入力" value="<?php if (!empty($_POST["userid"])) {echo htmlspecialchars($_POST["userid"], ENT_QUOTES);} ?>"></td>
                        </tr>
                        <tr>
                        <th><label for="password">パスワード:</label></th>
                        <td><input type="password" id="password" name="password" value="" placeholder="パスワードを入力"></td>
                        </tr>
                    </table>
                    <div class="button">
                        <input type="submit" id="login" name="login" value="ログイン">
                    </div>
                    <p class="text">パスワードを忘れた方はこちら</p>
                </fieldset>
            </div>
        </form>
        <br>
        <form action="id_insert.php">
            <div class="field">
                <fieldset>
                    <legend>新規登録フォーム</legend>
                    <div class="button">
                        <input type="submit" value="新規登録">
                    </div>
                </fieldset>
            </div>
        </form>
    <footer></footer>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="js/test.js"></script>
</body>
</html>