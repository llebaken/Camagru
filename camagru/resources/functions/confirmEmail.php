<?php
    include_once '../../config/setup.php';

    $username = $_GET['username'];
    $confirmcode = $_GET['confirmcode'];
    $confirm = 1;

    if($username != null && $confirmcode != null){
        try{
            $query = "SELECT * FROM users WHERE username = :username";
            $statement = $conn->prepare($query);
            $statement->bindParam(':username', $username);
            $statement->execute();
            if($statement->rowCount() == 1){
                $row = $statement->fetch();
                $db_confirmcode = $row['confirmcode'];
                if($confirmcode == $db_confirmcode){
                    $confirmcode = 0;
                    try{
                        $query = "UPDATE users SET confirm = :confirm, confirmcode = :confrimcode WHERE username = :username";
                        $statement = $conn->prepare($query);
                        $statement->bindParam(':confirm', $confirm);
                        $statement->bindParam(':confrimcode', $confirmcode);
                        $statement->bindParam(':username', $username);
                        $statement->execute();
                        header("location: ../../authentication/login.php");
                    }catch(PDOException $ex){
                        echo $ex->getMessage();                    }
                }else{
                    echo "Error occured code : 1112<br>";
                }
            }
                
        }catch(PDOException $ex){
            echo $ex->getMessage();
        }
    }else{
        echo "Error occured code : 1111<br>";
    }
?>