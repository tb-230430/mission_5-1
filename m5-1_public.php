<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        
        <?php
            
        //データベースへの接続
        $dsn='データベース名';
        $user='ユーザー名';
        $password='パスワード';
        $pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        //テーブルの作成
        $sql="CREATE TABLE IF NOT EXISTS notice_board"
        ."("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        ."date TEXT,"
        ."password TEXT"
        .");";
        $stmt=$pdo->query($sql);
        
        /*テーブル一覧を表示
        $sql='SHOW TABLES';
        $result=$pdo->query($sql);
        foreach($result as $row){
            echo $row[0];
            echo '<br>';
        }
        echo "<hr>";*/
        
        /*テーブルの構成詳細を確認
        $sql ='SHOW CREATE TABLE notice_board';
        $result = $pdo -> query($sql);
        foreach ($result as $row){
            echo $row[1];
        }
        echo "<hr>";*/
        
        
        
        //変数の指定（名前・コメント・パスワード）
        if(isset($_POST["namae"])){
            $name=$_POST["namae"];
        }
        if(isset($_POST["comment"])){
            $comment=$_POST["comment"];
        }
        if(isset($_POST["pass"])){
            $pass=$_POST["pass"];
        }
        //valueのエラー対策
        $editname="";
        $editcomment="";
        $editnum="";
        
        //投稿日時を取得
        $postdate=date("Y/m/d H:i:s");
        
        
        //変数の指定（編集モード）
        $edit="";
        if(isset($_POST["edit"])){
            $edit=$_POST["edit"];
        }
        
        
        //編集ボタンを押した場合
        if(isset($_POST["editpass"])){
            $editpass=$_POST["editpass"];
        }
        if(isset($_POST["submit3"])){
            //パスワードが一致したとき
            if($editpass=="pass"){
                $editnum=$_POST["editnum"];
                $sql='SELECT * FROM notice_board WHERE id=:id';
                $stmt=$pdo->prepare($sql);
                $stmt->bindParam(':id',$editnum,PDO::PARAM_INT);
                $stmt->execute();
                $results=$stmt->fetchAll();
                foreach($results as $row){
                    $edit=$row['id'];
                    $editname=$row['name'];
                    $editcomment=$row['comment'];
                }
            }
        }
            
        
            
        //送信ボタンを押した場合
        if(isset($_POST["submit1"])){
            
            //パスワードが一致したとき
            if($pass=="pass"){
                
                //新規登録か編集かで場合分け
                if($edit!=""){
                    
                    //1.編集モード
                    $sql='UPDATE notice_board SET 
                        name=:name,comment=:comment,date=:date WHERE id=:id';
                    $stmt=$pdo->prepare($sql);
                    $stmt->bindParam(':name',$name,PDO::PARAM_STR);
                    $stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
                    $stmt->bindParam(':date',$postdate,PDO::PARAM_STR);
                    $stmt->bindParam(':id',$edit,PDO::PARAM_INT);
                    $stmt->execute();
                    $edit="";
                    
                }else{
                    
                    //2.新規登録モード
                    $sql=$pdo->prepare("INSERT INTO notice_board(name,comment,date,password)
                        VALUES(:name,:comment,:date,:password)");
                    $sql->bindParam(':name',$name,PDO::PARAM_STR);
                    $sql->bindParam(':comment',$comment,PDO::PARAM_STR);
                    $sql->bindParam(':date',$postdate,PDO::PARAM_STR);
                    $sql->bindParam(':password',$pass,PDO::PARAM_STR);
                    $sql->execute();
                    
                }
            }
        }
            
         
           
        //削除ボタンを押した場合
        if(isset($_POST["deletepass"])){
            $deletepass=$_POST["deletepass"];
        }
            
        if(isset($_POST["submit2"])){
            //パスワードが一致したとき
            if($deletepass=="pass"){
                //削除対象番号を受信して場合分け
                $deletenum=$_POST["deletenum"];
                if(!empty($deletenum)){
                    $sql='delete from notice_board where id=:id';
                    $stmt=$pdo->prepare($sql);
                    $stmt->bindParam(':id',$deletenum,PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }
        
        
        ?>
        
        <!--フォームの作成-->
        <form action="" method="post">
            <p>名前：<input type="text" name="namae" 
                    value="<?php echo $editname; ?>">
            <p>コメント：<input type="text" name="comment"
                    value="<?php echo $editcomment; ?>">
                <input type="hidden" name="edit"
                    value="<?php echo $edit; ?>"></p>
            <p>パスワード：<input type="password" name="pass">
            <!--送信ボタン--><input type="submit" name="submit1"></p>
            
            <p>＜削除＞</p>
            <p>削除対象番号：<input type="number" name="deletenum"></p>
            <p>パスワード：<input type="password" name="deletepass">
            <!--削除ボタン--><input type="submit" name="submit2" value="削除"></p>
                
            <p>＜編集＞</p>
            <p>編集対象番号：<input type="number" name="editnum"></p>
            <p>パスワード：<input type="password" name="editpass">
            <!--編集ボタン--><input type="submit" name="submit3" value="編集"></p>
        </form>
        
        
        <?php
        //データを取得し、表示する
        $sql='SELECT * FROM notice_board';
        $stmt=$pdo->query($sql);
        /*$results=$stmt->fetchAll();*/
        foreach($stmt as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
        echo "<hr>";
        }
        ?>
        
    </body>
</html>