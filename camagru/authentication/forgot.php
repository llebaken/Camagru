<?php
    include_once '../config/setup.php';
    include_once '../resources/functions/functions.php';
    
    if(isset($_POST['submit'])){
        $email = $_POST['email'];
        $form_errors = array();
        $form_errors = check_email($_POST);

        if(empty($form_errors)){
            try{
                $query = "SELECT * FROM users WHERE email = :email";
                $statement = $conn->prepare($query);
                $statement->bindParam(':email', $email);
                $statement->execute();
                if($statement->rowCount() == 1){
                    $row = $statement->fetch();
                    $to = $row['email'];
                    $subject = "Change password request";
                    $text = "Please click the link below to change password
                        http://127.0.0.1:8080/camagru/authentication/newpassword.php?email=$to";
                    $headers = "From: DoNotReply@camagru.com";
                    $mail = mail($to,$subject,$text,$headers);
                    if($mail){
                        $result = "<p style='color:green;'> Please check email to to change password</p>";
                    }else{
                        $result = "<p style='color:red;'> Email not Sent</p>";
                    }
                }

            }catch(PDOException $ex){
                $result = "<p style='color:red;'> An error occured".$ex->getMessage()."</p>";
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
                <div>
                    <a class="nav-link" href="login.php">Login</a>
                    <a class="nav-link" href="signup.php">Signup</a>
                </div>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col align-self-center">
                    <img src="../resources/images/forgotpassword.png" alt="robot forgot password">
                </div>
                <div class="col">
                    <h1>Forgot Password</h1>
                    <?php if(isset($result)) echo $result; ?>
                    <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
                        </div>
                        <input type="submit" value="Submit" name="submit" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>