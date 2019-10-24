<?php
    include_once '../resources/session.php';
    include_once '../config/setup.php';

    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        try{
            $query = "SELECT * FROM users WHERE username = :username";
            $statement = $conn->prepare($query);
            $statement->bindParam(':username', $username);
            $statement->execute();
            if($statement->rowCount() == 1){
                $row = $statement->fetch();
                $username = $row['username'];
                $id = $row['id'];
                $hashed_password = $row['password'];
                $verified = $row['confirm'];

                if($verified == 1){
                    if(password_verify($password, $hashed_password)){
                        $_SESSION['id'] = $id;
                        header("location: ../gallery/public.php");
                    }else{
                        $result = "<p style='color:red;'> Incorrect password or email</p>";
                    }
                }else{
                    $result = "<p style='color:red;'> Please verify account</p>";
                }
            }else{
                $result = "<p style='color:red;'> Incorrect password or email</p>";
            }
        }catch(PDOException $ex){
            $result = "<p style='color:red;'> An error occured".$ex->getMessage()."</p>";
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
                    <a class="nav-link" href="#">Login</a>
                    <a class="nav-link" href="signup.php">Signup</a>
                </div>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col align-self-center">
                    <img src="../resources/images/camera.png" alt="camera image" width="300" height="300">
                </div>
                <div class="col">
                    <h1>Login</h1>
                    <?php if(isset($result)) echo $result; ?>
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" id="username" placeholder="Enter username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" required>
                        </div>
                        <input type="submit" value="Log In" name="login" class="btn btn-primary">
                        <p>Forgot Password <a href="forgot.php">Click Here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>