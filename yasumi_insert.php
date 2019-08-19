<?PHP
session_start();
$id_key = $_SESSION["id"].'-'.$_POST["date"];
$id = $_SESSION["id"];
$date = $_POST["date"];
$bunrui = $_POST["bunrui"];
$bikou = $_POST["bikou"];

try{
    $pdo = new PDO('mysql:dbname=kadai03_db;charset=utf8;host=localhost','root','');
    } catch (PDOException $e) {
    exit('DbConnectError:'.$e->getMessage());
    }

$stmt = $pdo->prepare("INSERT INTO yasumi_table(id_key,id,date, bunrui,bikou,indate) 
        VALUES (:id_key, :id, :date, :bunrui, :bikou, sysdate()) 
        ON DUPLICATE KEY UPDATE bunrui = :bunrui, bikou = :bikou");
$stmt->bindValue(':id_key', $id_key, PDO::PARAM_STR);  
$stmt->bindValue(':id', $id, PDO::PARAM_INT);  
$stmt->bindValue(':date', $date, PDO::PARAM_STR);  
$stmt->bindValue(':bunrui', $bunrui, PDO::PARAM_STR);  
$stmt->bindValue(':bikou', $bikou, PDO::PARAM_STR);  
$status = $stmt->execute();

if($status==false){
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
  }else{
    $_SESSION['tourokumessage'] = '以下の通り登録しました';
    $_SESSION['tourokumessage'] .= '<table><tr><th>日　　付：</th><td>';
    $_SESSION['tourokumessage'] .= $date ;
    $_SESSION['tourokumessage'] .= '</td></tr><br><tr><th>休日分類：</th><td>';
    $_SESSION['tourokumessage'] .= $bunrui ;
    $_SESSION['tourokumessage'] .= '</td></tr><br><tr><th>備　　考：</th><td>';
    $_SESSION['tourokumessage'] .= $bikou ;
    $_SESSION['tourokumessage'] .= '</td></tr></table>';
    header("Location: input.php");
    exit;
  }
?>