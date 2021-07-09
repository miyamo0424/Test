<html>
<html lang="ja">
<head>
</head>
<body>
<?php
$dsn = 'mysql:dbname=co_***_it_3919_com;host=localhost';
$user = 'co-***.it.3919.c';
$password = ʼPASSWORD';
$pdo = new PDO($dsn,$user,$password);
$sql = "CREATE TABLE IF NOT EXISTS KEI"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "date TEXT,"
. "pass TEXT"
.");";
$stmt = $pdo->query($sql);
    $d = false;
    $ca = "";

    if(!empty($_POST["copy"])){
		if(is_numeric($_POST["change"])){
        $na = getName($_POST["change"],$pdo);
        $co = getCo($_POST["change"],$pdo);
        $ca = $_POST["change"];

        }
    }else{
        $na = "";
        $co = "";
            if(!empty($_POST["name"]) or !empty($_POST["co"]))
            {				//名前欄とコメント欄のどちらかが入っていれば
                if(empty($_POST["pass"]))//パスワードが入ってなけ	れば
                {
                    echo "パスワードを入力してください<br>";
                }else{
					$name = getName($_POST["change"],$pdo);
					$str = getCo($_POST["change"],$pdo);
					if(!empty($_POST["name"])) $name= $_POST["name"];
					if(!empty($_POST["co"])) $str= $_POST["co"];
					changeline($_POST["change"],$name,$str,$pdo);
				
				}
			}
		}
    if(!empty($_POST["del"])){
		if(empty($_POST["pass"]))
        {
			echo "パスワードを入力してください<br>";
        }else{
			if($_POST["pass"] == getPass($_POST["del"],$pdo)) deleteline($_POST["del"],$pdo);
		}
	}
    if(empty($_POST["name"]) or empty($_POST["co"])){
		echo "名前と内容を入力してください<br>";
		}else{   
			if(empty($_POST["pass"]))
            {
				echo "	パスワードを入力してください<br>";
            }else{
                if(empty($_POST["change"])){
                $sql = $pdo -> prepare("INSERT INTO KEI (name, comment,date,pass) VALUES (:name, :comment,:date,:pass)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                $name = $_POST["name"];
                $comment = $_POST["co"]; //好きな名前、好きな言葉は自分で決めること
                $date = date("Y/m/s H:i:s");
                $pass = $_POST["pass"];
                $sql -> execute();
            }
            }
        }
$sql = 'SELECT * FROM KEI';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
    //$rowの中にはテーブルのカラム名が入る
    echo $row['id'].'<>';
    echo $row['name'].'<>';
    echo $row['comment'].'<>';
    echo $row['date'].'<br>';
echo "<hr>";
}
function getPass($ida,$pdo){
	$sql = 'SELECT * FROM KEI WHERE id=:id ';
    $id = $ida;
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                       // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    foreach ($results as $row){
    return $row["pass"];
	}		
	
}
function getName($ida,$pdo){
	$sql = 'SELECT * FROM KEI WHERE id=:id ';
    $id = $ida;
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                       // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    foreach ($results as $row){
	return $row["name"];
}		
}
function getCo($ida,$pdo){
	$sql = 'SELECT * FROM KEI WHERE id=:id ';
    $id = $ida;
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                       // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    foreach ($results as $row){
    return $row["comment"];

	}		
}
function getDat($ida,$pdo){
	$sql = 'SELECT * FROM KEI WHERE id=:id ';
    $id = $ida;
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                       // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    foreach ($results as $row){
    return $row["pass"];
	}		
}
function changeline($line,$nam,$str,$pdo){
    $id = $line; //変更する投稿番号
    $name = $nam;
    $comment = $str; //変更したい名前、変更したいコメントは自分で決めること
    $sql = 'UPDATE KEI SET name=:name,comment=:comment WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    }
function deleteline($line,$pdo){
    $id = $line;
    $sql = 'delete from KEI where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

}
    if(!empty($_POST["alld"])){
        $sql = 'DROP TABLE KEI';
        $stmt = $pdo->query($sql);
    }

?>

    <form method="POST" action="">
        お名前：<input type="text" name="name" value="<?php echo $na;?>">　削除：<input type="text" name="del">
        変更：<input type="text" name="change" size="1" value="<?php echo $ca;?>"><input type="submit" name="copy" value="コピー"><br>
        　内容：<input type="text" name="co" value= "<?php echo $co;?>"><br>
        　パス：<input type="text" name="pass" value= "">
        <input type="submit" name="submit" value="送信">
        <br><input type="submit" name="alld" value="全消">
    </form>
    </body>
    </html>