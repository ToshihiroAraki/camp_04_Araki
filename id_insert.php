<?php
require ('include/password.php');  
// セッション開始
session_start();
// エラーメッセージ、登録完了メッセージの初期化
$errormessage = "";
$signUpmessage = "";
// ログインボタンが押された場合
if (isset($_POST["signUp"])) {
    // 1. ユーザIDの入力チェック
    if (empty($_POST["name"])) {  // 値が空のとき
        $errormessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["password"])) {
        $errormessage = 'パスワードが未入力です。';
    } else if (empty($_POST["password2"])) {
        $errormessage = 'パスワードが未入力です。';
    }

    if (!empty($_POST["name"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password"] === $_POST["password2"]) {
        // 入力したユーザIDとパスワードを格納
        $username = $_POST["name"];
        $password = $_POST["password"];
        $busho = $_POST["busho"];
        $shift = $_POST["shift"];

        try {
            $pdo = new PDO('mysql:dbname=kadai03_db;charset=utf8;host=localhost','root','');
            } catch (PDOException $e) {
                $errormessage = 'データベースエラー';
            }
            $stmt = $pdo->prepare("INSERT INTO id_table(id,name, password,busho,shift,indate) VALUES (?, ?, ?, ?, ?, sysdate())");
            $status = $stmt->execute(array(NULL, $username, password_hash($password, PASSWORD_DEFAULT), $busho, $shift));  // パスワードのハッシュ化を行う（今回は文字列のみなのでbindValue(変数の内容が変わらない)を使用せず、直接excuteに渡しても問題ない）
            if($status==false){
                $errormessage = $stmt->errorInfo();
                exit("QueryError:".$errormessage[2]);
              }else{
            $userid = $pdo->lastinsertid();  // 登録した(DB側でauto_incrementした)IDを$useridに入れる
            $signUpmessage = '登録が完了しました。あなたの登録IDは '. $userid. ' です。パスワードは '. $password. ' です。';  // ログイン時に使用するIDとパスワード
              }
    } else if($_POST["password"] != $_POST["password2"]) {
        $errormessage = 'パスワードに誤りがあります。';
    }
}
include('include/head.php');
?>
    <title>新規登録</title>
</head>
<body>
    <h1>勤怠管理システム</h1>
    <header class="header2">
        <div class="dummy"></div>
    </header>
    <h2>新規登録画面</h2>
    <div class="field">
        <form id="loginForm" name="loginForm" action="" method="POST">
            <fieldset>
                <legend>新規登録フォーム</legend>
                <div>
                <?php echo '<P class="error">'.$errormessage.'</p>'; ?>
                </div>
                <div>
                <?php echo '<P class="error">'.$signUpmessage.'</p>'; ?>
                </div>
                <table>
                    <tr>
                        <th><label for="busho">部署名　　　　　　：</label></th>
                        <td><input type="text" id="busho" name="busho" placeholder="部署名を入力" value="" required></td>
                    </tr>
                    <tr>
                        <th><label for="name">名前　　　　　　　：</label></th>
                        <td><input type="text" id="name" name="name" placeholder="名前を入力" required value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>"></td>
                    </tr>
                        <tr>
                        <th><label for="shift">シフト　　　　　　：</label></th>
                        <td><select name="shift" id="shift" required>
                        <option value="">選択してください</option>
                        <option value="A">A : 08:30 ~ 17:30</option>
                        <option value="B">B : 09:00 ~ 18:00</option>
                        <option value="C">C : 10:00 ~ 19:00</option>
                        </select></td>
                    </tr>
                    <tr>
                        <th><label for="password">パスワード　　　　：</label></th>
                        <td><input type="password" id="password" name="password" value="" placeholder="パスワードを入力" required></td>
                    </tr>
                    <tr>
                        <th><label for="password2">パスワード(確認用)：</label></th>
                        <td><input type="password" id="password2" name="password2" value="" placeholder="再度パスワードを入力" required></td>
                    </tr>
                </table>
                <div class="button">
                    <input type="submit" id="signUp" name="signUp" value="新規登録">
                </div>
            </fieldset>
        </form>
        <br>
        <form action="index.php">
            <div class="button">
                <input type="submit" value="戻る">
            </div>
        </form>
    </div> 
    <footer></footer>
</body>
</html>