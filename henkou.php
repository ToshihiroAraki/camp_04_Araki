<?php
require ('include/password.php');  
session_start();
$errormessage="";
$signUpmessage="";

if(isset($_POST["signUp"])){
    if((empty($_POST["busho"])) && (empty($_POST["name"])) && (empty($_POST["shift"])) && (empty($_POST["password"]))) {
        $errormessage = "変更内容が入力されていません";
    }else{
        $sql = "";
        if(!empty($_POST["busho"])){
            $_SESSION["busho"] = $_POST["busho"];
            $sql .= "UPDATE id_table SET busho = '".$_POST["busho"]."' WHERE id = ".$_SESSION["id"].";";
        }
        if(!empty($_POST["name"])){
            $_SESSION["name"] = $_POST["name"];
            $sql .= "UPDATE id_table SET name = '".$_POST["name"]."' WHERE id = ".$_SESSION["id"].";";
        }
        if(!empty($_POST["shift"])){
            $_SESSION["shift"] = $_POST["shift"];
            $sql .= "UPDATE id_table SET shift = '".$_POST["shift"]."' WHERE id = ".$_SESSION["id"].";";
        }
        if(!empty($_POST["password"])){
            $_SESSION["password"] = $_POST["password"];
            $sql .= "UPDATE id_table SET password = '".password_hash($_POST["password"], PASSWORD_DEFAULT)."' WHERE id = ".$_SESSION["id"].";";
        }
        try {
            $pdo = new PDO('mysql:dbname=kadai03_db;charset=utf8;host=localhost','root','');
            } catch (PDOException $e) {
                $errormessage = 'データベースエラー';
            }
        $stmt = $pdo->prepare($sql);
        $status = $stmt->execute();
        if($status==false){
            $errormessage = $stmt->errorInfo();
            exit("QueryError:".$errormessage[2]);
        }else{
        $signUpmessage = '変更が完了しました。変更後の登録情報はこちらです。'; 
        }
    }
}
include('include/head.php');
?>
    <title>ユーザー情報変更</title>
</head>
<body>
    <?php include('include/header.php'); ?>
    <h2>ユーザー情報変更画面</h2>
    <div class="jouhou">
    <div class="field">
        <fieldset>
            <legend>現在の登録情報</legend>
            <div>
                <?php 
                echo '<P class="error">'.$signUpmessage.'</p>';
                ?>
            </div>
                <table>
                    <tr>
                        <th><label for="busho">部署名　　：</label></th>
                        <td><?PHP echo $_SESSION['busho'];?></td>
                    </tr>
                    <tr>
                        <th><label for="name">名前　　　：</label></th>
                        <td><?PHP echo $_SESSION['name'];?></td>
                    </tr>
                        <tr>
                        <th><label for="shift">シフト　　：</label></th>
                        <td><?PHP echo $_SESSION['shift'];?></td>
                    </tr>
                    <tr>
                        <th><label for="password">パスワード：</label></th>
                        <td><?PHP echo $_SESSION['password'];?></td>
                    </tr>
                </table>
        </fieldset>
    </div>
    <form id="loginForm" name="loginForm" action="" method="POST">
        <div class="field">        
            <fieldset>
                <legend>登録情報変更フォーム</legend>
                <div>
                <?php 
                echo '<P class="error">'.$errormessage.'</p>';
                ?>
                </div>
                <table>
                    <tr>
                        <th><label for="busho">部署名　　：</label></th>
                        <td><input type="text" id="busho" name="busho" placeholder="変更する場合のみ入力" value=""></td>
                    </tr>
                    <tr>
                        <th><label for="name">名前　　　：</label></th>
                        <td><input type="text" id="name" name="name" placeholder="変更する場合のみ入力" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>"></td>
                    </tr>
                        <tr>
                        <th><label for="shift">シフト　　：</label></th>
                        <td><select name="shift" id="shift">
                        <option value="">変更する場合のみ選択</option>
                        <option value="A">A : 08:30 ~ 17:30</option>
                        <option value="B">B : 09:00 ~ 18:00</option>
                        <option value="C">C : 10:00 ~ 19:00</option>
                        </select></td>
                    </tr>
                    <tr>
                        <th><label for="password">パスワード：</label></th>
                        <td><input type="password" id="password" name="password" value="" placeholder="変更する場合のみ入力"></td>
                    </tr>
                </table>
                <div class="button">
                    <input type="submit" id="signUp" name="signUp" value="新規登録">
                </div>
            </fieldset>
        </div>
    </form>
    </div>
    <footer></footer>
</body>
</html>