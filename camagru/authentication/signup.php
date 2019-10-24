<?php 
    include_once '../config/setup.php';
    include_once '../resources/functions/functions.php';

    if(isset($_POST['signup'])){
        $form_errors = array();
        $form_errors = array_merge($form_errors, check_username($_POST));
        $form_errors = array_merge($form_errors, check_email($_POST));
        $form_errors = array_merge($form_errors, cheack_password($_POST));

        if(empty($form_errors)){
            $email = $_POST['email'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $confirmcode = rand(0,99999);
            $confirm = 0;
            $notify = 1;
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try{
                $sql = "INSERT INTO users (username, email, password, confirmcode, confirm, notify, join_date)
                        VALUES (:username, :email, :password, :confirmcode, :confirm, :notify, now())";
                $statement = $conn->prepare($sql);
                $statement->bindParam(':username', $username);
                $statement->bindParam(':email', $email);
                $statement->bindParam(':password', $hashed_password);
                $statement->bindParam(':confirmcode', $confirmcode);
                $statement->bindParam(':confirm', $confirm);
                $statement->bindParam(':notify', $notify);
                $statement->execute();

                if($statement->rowCount() == 1){
                    $to = $email;
                    $subject = "Camagru: Confirm Email";
                    $text = "Please click the link below to confirm your account
                    http://127.0.0.1:8080/camagru/resources/functions/confirmEmail.php?username=$username&confirmcode=$confirmcode";
                    $headers = "From: DoNotReply@camagru.com";
                    $mail = mail($to,$subject,$text,$headers);
                    if($mail){
                        $result = "<p style='color:green;'> Please check email to confirm email address</p>";
                    }else{
                        $result = "<p style='color:red;'> Email not Sent</p>";
                    }
                }

            }catch(PDOException $ex){
                $result = "<p style='color:red;'> An error occured".$ex->getMessage()."</p>";
                echo $result;
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
                    <a class="nav-link" href="#">Signup</a>
                </div>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col align-self-center">
                    <img src="../resources/images/camera.png" alt="camera image" width="300" height="300">
                </div>
                <div class="col">
                    <h1>Signup</h1>
                    <form method="post" action="">
                    <?php if(isset($result)) echo $result; ?>
                    <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" id="username" placeholder="Enter username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" required>
                        </div>
                        <div class="form-group">
                            <label for="repassword">Password</label>
                            <input type="password" name="repassword" class="form-control" id="repassword" placeholder="Re-Enter Password" required>
                        </div>
                        <input type="submit" value="Sign Up" name="signup" class="btn btn-primary">
                        <p>Already have an account <a href="login.php">Click Here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>