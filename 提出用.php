<html>
<head>
<title>掲示板</title>
<meta charset= "utf-8">
</head>

<body>

 <?php
 
 $name = "";
 $comment = "";
 $pass = "";
 $editN = "";
 $time = date("Y年m月d日　H時i分s秒");

       //データベースに接続   
    $dsn = 'データベース名';
    $user='ユーザー名';
    $password='パスワードʼ';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)); 


      //テーブルの作成
$sql = "CREATE TABLE IF NOT EXISTS tech_test"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name TEXT," //char(32)
    . "comment TEXT,"
    . "time TEXT,"
    . "pass TEXT"
    .");";
    $stmt = $pdo->query($sql);

//投稿ボタンが押されたとき
if(!empty($_POST["submit"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){
    $name=$_POST["name"];
    $comment=$_POST["name"];
    $editN =$_POST["editNum"];
    $pass=$_POST["pass"];



//編集の場合
if(!empty($editN)){
    $sql = $pdo -> prepare('UPDATE tech_test SET name=:name,comment=:comment,time=:time,pass=:pass WHERE id=:id');
    $sql->bindParam(':id', $editN, PDO::PARAM_INT);
    $sql->bindParam(':name', $name, PDO::PARAM_STR);
    $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql->bindParam(':time', $time, PDO::PARAM_STR);
    $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
    $sql->execute(); 
    ////////echo "編集完了です！";
    
    }else{
        
    $sql = $pdo -> prepare('INSERT INTO tech_test(name,comment,time,pass) VALUE(:name,:comment,:time,:pass)');   
    $sql->bindParam(':name', $name, PDO::PARAM_STR);
    $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql->bindParam(':time', $time, PDO::PARAM_STR);
    $sql->bindParam(':pass', $pass, PDO::PARAM_STR);
    $sql->execute(); 
    echo "投稿完了です！";
    }
}


//削除が押されたとき
if(!empty($_POST["deleNum"]) && !empty($_POST["delePass"]) ){
    $deleNum=$_POST["deleNum"];
    $delePass=$_POST["delePass"];
    //パスワードをデータベースから取得
    $stmt = $pdo->prepare("DELETE FROM tech_test WHERE id = :id AND pass= :pass");
    $stmt->bindParam(':id', $deleNum, PDO::PARAM_INT);
    $stmt->bindParam(':pass', $delePass, PDO::PARAM_STR);
    $stmt ->execute(); 
       ////////// echo "削除しました！";   
    }




//編集ボタンが押されたとき
if(!empty($_POST["editNum"]) && !empty($_POST["editPass"])){
    $editN = $_POST["editNum"];
    $editPass = $_POST["editPass"];
//パスワードをデータベースから取得
    $stmt = $pdo->prepare("SELECT id,name,comment,pass FROM tech_test WHERE id=:id AND pass=:pass");
    $stmt -> bindParam(':id', $editN, PDO::PARAM_INT);
    $stmt -> bindParam(':pass', $editPass, PDO::PARAM_INT);
    $stmt ->execute(); 
    ///////////echo "編集モードです";
    
    $results = $stmt ->fetchAll();
    foreach($results as $row){
        $editN=$row["id"];
        $name=$row["name"];
        $comment=$row["comment"];
        $pass=$row["pass"];  
}

//編集する投稿の情報があっていた場合
/*
if($editPass == $correctPass && $editN == $correctId){
    echo "編集モードです";
    //編集する投稿の情報をさらに取得
    $stmt = $pdo->prepare("SELECT name,comment FROM tech_test ");
    $stmt -> bindParam(':id', $editN, PDO::PARAM_INT);
    $stmt ->execute(); 
    $results = $stmt ->fetchAll();

foreach($results as $row){
    $name = $_POST["name"];
    $comment = $_POST["name"];
    $editN = $editN;
    $pass = $correctPass;
}

}else{
    echo "パスワードが間違っています";
}*/

    
    
}
?>  



<form method = "POST" action = "">
<input type = "text"  name = "name"  value = "<?php if(!empty($pass)){echo $name;}?>"  placeholder = "名前">
<input type = "text"  name = "comment"  value = "<?php if(!empty($pass)){echo $comment;}?>"  placeholder ="コメント" >
<input id="pass" type = "text" name = "pass" value ="<?php if(!empty($pass)){echo $pass;}?>"  placeholder = "パスワード">
<input type = "submit"  name = "submit" value = "送信"><br>

<input type = "text" name = "deleNum"  placeholder = "削除対象番号" placeholder="編集番号を入力してください">
<input id="pass" type = "text" name = "delePass"  placeholder = "パスワード">
<input type = "submit"  name = "delete" value = "削除"><br>

<input type = "text" name = "editNum" placeholder = "編集対象番号" placeholder="編集番号を入力してください">
<input id="pass" type = "text" name = "editPass"  placeholder = "パスワード">
<input type =  "submit" name = "edit" value = "編集"><br> 






<?php
//表示機能

    $sql = 'SELECT * FROM tech_test';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();

    foreach ($results as $row){
        //配列の中で使うのはテーブルのカラム名の物
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['time'].'<br>';
        echo "<hr>";
    }
?>
</body>
</html>