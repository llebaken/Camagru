<?php
    include_once '../config/setup.php';
    include_once '../resources/functions/functions.php';

    $email = $_GET['email'];
    if(isset($_POST['submit'])){
        $form_errors = array();
        $form_errors = array_merge($form_errors, cheack_password($_POST));
        if(empty($form_errors)){
            if(isset($email)){
                try{
                    $query = "SELECT * FROM users WHERE email = :email";
                    $statement = $conn->prepare($query);
                    $statement->bindParam(':email', $email);
                    $statement->execute();
                    if($statement->rowCount() == 1){
                        $row = $statement->fetch();
                        $email = $row['email'];
                            try{
                                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                                $query = "UPDATE users SET password = :password WHERE email = :email";
                                $statement = $conn->prepare($query);
                                $statement->bindParam(':password', $hashed_password);
                                $statement->bindParam(':email', $email);
                                $statement->execute();
                                header("location: login.php");
                            }catch(PDOException $ex){
                                $result = "<p style='color:red;'> An error occured".$ex->getMessage()."</p>";
                            }
                    }
                }catch(PDOException $ex){
                    $result = "<p style='color:red;'> An error occured".$ex->getMessage()."</p>";
                }
            }else{
                $result = "<p style='color:red;'> Something Went wrong</p>";
            }
        }
    }
?>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="../resources/css/authentication.css">
    </head>
    <body>
        <div class="header">
            <nav class="navbar">
                <a class="navbar-brand" id="icon" href="../index.php">Camagru</a>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col align-self-center">
                    <img src="../resources/images/newpassword.jpg" alt="camera image" width="300" height="300">
                </div>
                <div class="col">
                    <h1>Forgot Password</h1>
                    <?php if(isset($result)) echo $result; ?>
                    <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" required>
                        </div>
                        <div class="form-group">
                            <label for="repassword">Password</label>
                            <input type="password" name="repassword" class="form-control" id="repassword" placeholder="Re-Enter Password" required>
                        </div>
                        <input type="submit" value="Submit" name="submit" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>