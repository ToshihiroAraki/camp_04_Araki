<?PHP
session_start();
//kintai_tableは同じidのデータが複数あり、ユニークに出来ないのでidとdateを結合した文字列をユニークにして、
//ON DUPLICATE KEY UPDATEを使えるようにする。
$id_key = $_SESSION["id"].'-'.$_POST["date"];
$id = $_SESSION["id"];
$date = $_POST["date"];
$starttime = $_POST["starttime"];
$endtime = $_POST["endtime"];
try{
    $pdo = new PDO('mysql:dbname=kadai03_db;charset=utf8;host=localhost','root','');
    } catch (PDOException $e) {
    exit('DbConnectError:'.$e->getMessage());
    }

$stmt = $pdo->prepare("INSERT INTO kintai_table(id_key,id,date, starttime,endtime,indate) 
        VALUES (:id_key, :id, :date, :starttime, :endtime, sysdate()) 
        ON DUPLICATE KEY UPDATE starttime = :starttime, endtime = :endtime");
$stmt->bindValue(':id_key', $id_key, PDO::PARAM_STR);  
$stmt->bindValue(':id', $id, PDO::PARAM_INT);  
$stmt->bindValue(':date', $date, PDO::PARAM_STR);  
$stmt->bindValue(':starttime', $starttime, PDO::PARAM_STR);  
$stmt->bindValue(':endtime', $endtime, PDO::PARAM_STR);  
$status = $stmt->execute();

if($status==false){
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
  }else{
    $_SESSION['kanryomessage'] = '以下の通り登録しました';
    $_SESSION['kanryomessage'] .= '<table><tr><th>日　　付：</th><td>';
    $_SESSION['kanryomessage'] .= $date ;
    $_SESSION['kanryomessage'] .= '</td></tr><br><tr><th>出社時刻：</th><td>';
    $_SESSION['kanryomessage'] .= $starttime ;
    $_SESSION['kanryomessage'] .= '</td></tr><br><tr><th>退社時刻：</th><td>';
    $_SESSION['kanryomessage'] .= $endtime ;
    $_SESSION['kanryomessage'] .= '</td></tr></table>';
    header("Location: input.php");
    exit;
  }
?>