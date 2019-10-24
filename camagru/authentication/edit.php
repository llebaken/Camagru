<?php
    include_once '../resources/session.php';
    include_once '../config/setup.php';
    include_once '../resources/functions/functions.php';
    
    if(!isset($_SESSION['id'])){
        header("../index.php");
    }

    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];

        try{
            $query = "SELECT * FROM users WHERE id = :id";
            $statement = $conn->prepare($query);
            $statement->bindParam(':id', $id);
            $statement->execute();
            if($statement->rowCount() == 1){
                $row = $statement->fetch();
                $db_username = $row['username'];
                $db_email = $row['email'];
            }
        }catch(PDOException $ex){
            $error = $ex->getMessage();
        }
    }

    if(isset($_POST['submit'])){
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $re_password = $_POST['repassword'];
        if($_POST['notify'] == "Yes")
        {
            $notify = 1;
        }else{
            $notify = 0;
        } 
        $form_errors = array();

        if(strcmp($email, $db_email) != 0){
            $form_errors = array_merge($form_errors, check_email($_POST));
        }
        if(strcmp($username, $db_username) != 0){
            $form_errors = array_merge($form_errors, check_username($_POST));
        }
        if(strlen(trim($password)) != 0 && $password != NULL){
            $form_errors = array_merge($form_errors, cheack_password($_POST));
        }

        if(empty($form_errors)){
            $db_email = $email;
            $db_username = $username;
            $db_password = $password;
            if(isset($db_password)){
                try{
                    $hashed_password = password_hash($db_password, PASSWORD_DEFAULT);
                    $query = "UPDATE users SET username = :username, password = :password, email = :email , notify = :notify WHERE id = :id";
                    $statement = $conn->prepare($query);
                    $statement->bindParam(':username', $db_username);
                    $statement->bindParam(':password', $hashed_password);
                    $statement->bindParam(':email', $db_email);
                    $statement->bindParam(':notify', $notify);
                    $statement->bindParam(':id', $id);
                    $statement->execute();
                }catch(PDOException $ex){
                    $result = "<p style='color:red;'> An error occured".$ex->getMessage()."</p>";
                    echo $result;
                }
            }else{
                try{
                    $query = "UPDATE users SET username = :username, email = :email, notify = :notify  WHERE id = :id";
                    $statement = $conn->prepare($query);
                    $statement->bindParam(':username', $db_username);
                    $statement->bindParam(':email', $db_email);
                    $statement->bindParam(':notify', $notify);
                    $statement->bindParam(':id', $id);
                    $statement->execute();
                }catch(PDOException $ex){
                    $result = "<p style='color:red;'> An error occured".$ex->getMessage()."</p>";
                    echo $result;
                }
            }
        }

    

    }

?>

<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="../resources/css/gallery.css">
    </head>
    <body>
        <div class="header">
        <nav class="navbar">
                <a class="navbar-brand" id="icon" href="../gallery/public.php">Camagru</a>
                <div>
                    <a class="nav-link" href="../gallery/upload.php">Camera</a>
                    <div class="dropdown">
                        <button class="dropbtn"><?php if(isset($db_username)) echo $db_username; ?></button>
                        <div class="dropdown-content">
                            <a href="#">Edit Profile</a>
                            <a href="../gallery/private.php">Private Gallery</a>
                            <a href="../resources/logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1>Edit Profile</h1>
                    <form method="post" action="">
                    <?php if(isset($result)) echo $result; ?>
                    <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" value= "<?php echo $db_email ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" id="username" value= "<?php echo $db_username ?>" placeholder="Enter username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">
                        </div>
                        <div class="form-group">
                            <label for="repassword">Password</label>
                            <input type="password" name="repassword" class="form-control" id="repassword" placeholder="Re-Enter Password">
                        </div>
                        <div class="form-group">
                            <label>would you like to be sent notifications:</label>
                            <select name="notify">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <input type="submit" value="Sumbit" name="submit" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>