<?php
// セッション開始
session_start();

// エラーメッセージ、登録完了メッセージの初期化
$errormessage = "";
$signUpmessage = "";
// ログインボタンが押された場合
if (isset($_POST["toukou"])) {

    if (!empty($_POST["bunrui"]) && !empty($_POST["title"]) && !empty($_POST["naiyou"])) {
        // 入力したユーザIDとパスワードを格納
        $bunrui = $_POST["bunrui"];
        $title = $_POST["title"];
        $naiyou = $_POST["naiyou"];
        $name = $_SESSION['name'];
        //添付fileがあるときだけ下記実行
        if(!empty($_FILES['upfile']['tmp_name'])){
            // $filepass = fopen($_FILES['upfile']['tmp_name'], "rb");
            // echo "<br>";
            // var_dump($filepass);
            // $pdf = fread($filepass, filesize($_FILES['upfile']['tmp_name']));
            // fclose($filepass);
            $filename = $_FILES['upfile']['name'];
            $storeDir = 'upload/';
            $new_filename = uniqid().$filename;
            //一時フォルダからC:\xampp\htdocs\camp_04_Araki.git\uploadのフォルダに移動させる
            //DBにはその保存nameを入れておき、selectした後にダウンロードさせる。
            move_uploaded_file($_FILES['upfile']['tmp_name'], $storeDir.$new_filename);
            $pdf = $new_filename;
        }else{
            $pdf = "";
        }

        try {
            $pdo = new PDO('mysql:dbname=kadai03_db;charset=utf8;host=localhost','root','');
            } catch (PDOException $e) {
                $errormessage = 'データベースエラー';
            }
            $stmt = $pdo->prepare("INSERT INTO board_table(no,bunrui, title,naiyou,name,indate,file) VALUES (?, ?, ?, ?, ?, sysdate(),?)");
            $status = $stmt->execute(array(NULL, $bunrui, $title, $naiyou, $name,$pdf));  
            if($status==false){
                $errormessage = $stmt->errorInfo();
                exit("QueryError:".$errormessage[2]);
              }else{
            $no = $pdo->lastinsertid(); 
            $signUpmessage = '登録が完了しました。記事Noは '. $no. ' です。';
              }
    } 
}
include('include/head.php');
?>
    <title>記事投稿</title>
</head>
<body>
    <?php include('include/header.php'); ?>
    <div class="mainbox">
        <div class="leftbox">
            <ul>
                <li><a href="main.php">掲示板</a></li>
                <li><a href="board_insert.php">投稿</a></li>
            </ul>
        </div>
        <div class="rightbox">
            <div class="field">
                <form id="toukouForm" name="toukouForm" action="board_insert.php" method="POST" enctype="multipart/form-data">
                    <fieldset>
                        <legend>記事投稿</legend>
                        <div>
                        <?php echo '<P class="error">'.$errormessage.'</p>'; ?>
                        </div>
                        <div>
                        <?php echo '<P class="error">'.$signUpmessage.'</p>'; ?>
                        </div>
                        <table>
                            <tr>
                                <th><label for="bunrui"> 分　類 ：</label></th>
                                <td><input type="radio" name="bunrui" id="osirase" value="お知らせ" checked>
                                    <label class="bunrui" for="osirase">お知らせ</label>
                                    <input type="radio" name="bunrui" id="zirei" value="辞令発令">
                                    <label class="bunrui" for="zirei">辞令発令</label>
                                    <input type="radio" name="bunrui" id="kitei" value="社内規定">
                                    <label class="bunrui" for="kitei">社内規定</label>
                                </td>
                            </tr>
                            <tr>
                                <th><label for="title">タイトル：</label></th>
                                <td><input type="text" id="title" name="title" placeholder="タイトルを入力" value="" required></td>
                            </tr>
                            <tr>
                                <th><label for="naiyou"> 内　容 ：</label></th>
                                <td><textarea id="naiyou" name="naiyou" rows="5" placeholder="" value="" required></textarea></td>
                            </tr>
                            <tr>
                                <th><label for="file">添付file：</label></th>
                                <td><input type="file" id="upfile" name="upfile" accept="application/pdf"></td>
                            </tr>
                            </table>
                            <div class="button">
                                <input type="submit" id="toukou" name="toukou" value="記事投稿">
                            </div>
                    </fieldset>
                </form>
            </div>
        </div>  
    </div> 
    <footer></footer>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="js/test.js"></script>
</html>