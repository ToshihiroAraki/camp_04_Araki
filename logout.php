<?php
session_start();
if (isset($_SESSION["name"])) {
    $message = "ログアウトしました。";
} else {
    $message = "セッションがタイムアウトしました。";
}
// セッションの変数のクリア
$_SESSION = array();
// セッションクリア
@session_destroy();
include('include/head.php');
?>
    <title>ログアウト</title>
</head>
<body>
    <h1>勤怠管理システム</h1>
    <header class="header2">
        <div class="dummy"></div>
    </header>
    <h2>ログアウト画面</h2>
        <div class="message"><?php echo $message; ?></div>
        <form action="index.php">
            <div class="button">
                <input type="submit" value="ログイン画面に戻る">
            </div>
        </form>
        <footer></footer>
</body>
</html>