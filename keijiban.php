<?php
  
    
    $こめんと = filter_input(INPUT_POST,"コメント");
    $なまえ = filter_input(INPUT_POST,"名前");
    $さくじょ = filter_input(INPUT_POST,"削除番号");
    $date=date("Y-m-d,H:i:s");
    $へんしゅうばんごう=filter_input(INPUT_POST,"編集");
    $編集される予定の番号=filter_input(INPUT_POST,"編集される予定の番号");
    $パスワード=filter_input(INPUT_POST,"password");
   
    // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    $sql = "CREATE TABLE IF NOT EXISTS m5_1"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"//自動で登録されているナンバリング。
    . "name char(32),"//名前を入れる。文字列、半角英数で32文字。
    . "comment TEXT,"//文字列
    ."date DATETIME,"//date
    ."pass varchar(32)"
    .");";
    $stmt = $pdo->query($sql);
    
    $sql = 'SELECT * FROM m5_1';
    $stmt = $pdo->query($sql); 
    $result=$stmt->fetchAll();

    
   //データ入力 
   if(!empty($なまえ)&&!empty($こめんと)&&!empty($パスワード)&&empty($編集される予定の番号)&&isset($_POST["投稿する"])){
    $sql = $pdo -> prepare("INSERT INTO m5_1 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
    $sql -> bindParam(':name', $なまえ, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $こめんと, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $パスワード, PDO::PARAM_STR);
    $sql ->execute();
    //bindParamの引数名（:name など）はテーブルのカラム名に併せるとミスが少なくなります。最適なものを適宜決めよう。
    
    
    //データの表示をする。
         $sql = 'SELECT * FROM m5_1';
        $stmt = $pdo->query($sql);
         $result = $stmt->fetchAll();
            foreach ($result as $row){
        //$rowの中にはテーブルのカラム名が入る
             echo $row['id'].',';
             echo $row['name'].',';
             echo $row['comment'].',';
             echo $row['date'].'<br>';
             echo "<hr>";
            }
        
   
    
    //入力されているデータレコードの内容を編集
}elseif(!empty($なまえ)&&!empty($パスワード)&&!empty($編集される予定の番号)&&isset($_POST["投稿する"])){//もし入力フォームに文字があって、編集される予定の番号も空じゃなかったら
    $id = $編集される予定の番号; //変更する投稿番号
    
    $sql = 'UPDATE m5_1 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $なまえ, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $こめんと, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
    $stmt -> bindParam(':pass', $パスワード, PDO::PARAM_STR);
    $stmt->execute();
    
     //データの表示をする。
        if($password==$パスワード){
         $sql = 'SELECT * FROM m5_1';
        $stmt = $pdo->query($sql);
         $result = $stmt->fetchAll();
            foreach ($result as $row){
        //$rowの中にはテーブルのカラム名が入る
             echo $row['id'].',';
             echo $row['name'].',';
             echo $row['comment'].',';
             echo $row['date'].'<br>';
             echo "<hr>";
            }
        }
        
        

    //入力したデータの削除
 }elseif(!empty($さくじょ)&&!empty($パスワード)&&isset($_POST["削除する"])){//削除フォームから削除番号が送信されたら？
    $id = $さくじょ;
    $sql = 'delete from m5_1 where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    
    //データの表示をする。
        if($password==$パスワード){
         $sql = 'SELECT * FROM m5_1';
        $stmt = $pdo->query($sql);
         $result = $stmt->fetchAll();
            foreach ($result as $row){
        //$rowの中にはテーブルのカラム名が入る
             echo $row['id'].',';
             echo $row['name'].',';
             echo $row['comment'].',';
             echo $row['date'].'<br>';
             echo "<hr>";
            }
        }

     
 }else{
$sql = 'SELECT * FROM m5_1';
$stmt = $pdo->query($sql);
$result=$stmt->fetchAll();
foreach ($result as $row){
echo $row['id'].',';
echo $row['name'].',';
echo $row['comment'].',';
echo $row['date'].'<br>';
echo "<hr>";
}
}


?>
<form method="post" action="">
    <input type="text" name="名前" placeholder="名前を入力" value=<?php
    if(!empty($へんしゅうばんごう)&&isset($_POST["編集する"])){
         foreach ($result as $row){
        if($row['id']==$へんしゅうばんごう){
             echo$row['name'];
          }
         }
    }
          ?>
          >
          
    <input type="text"name="コメント" placeholder="コメントを入力" value=<?php
    if(!empty($へんしゅうばんごう)&&isset($_POST["編集する"])){
        foreach ($result as $row){
        if($row['id']==$へんしゅうばんごう){
             echo$row['comment'];
          }
         }
    }
         
          ?>
        ><input type="submit" name="投稿する"value="投稿する"><br>

         
        <input type="number"name="削除番号"placeholder="削除番号"><input type="submit" name="削除する"value="削除する"><br>
    
        <input type="number" name="編集" placeholder="編集番号" ><input type="submit" name="編集する"value="編集する"><br>
        
        
         <input type="text" name="password" placeholder="パスワード"><br>
         
          <input type="hidden" name="編集される予定の番号" value=<?php
             if(!empty($へんしゅうばんごう)&&!empty($パスワード)&&isset($_POST["編集する"])){
                echo$へんしゅうばんごう;
                }
                ?>
                >
                
</form>
