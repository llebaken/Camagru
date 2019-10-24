<?php
    include_once '../resources/session.php';
    include_once '../config/setup.php';
    
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
                $username = $row['username'];
            }
        }catch(PDOException $ex){
            $error = $ex->getMessage();
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
                <a class="navbar-brand" id="icon" href="public.php">Camagru</a>
                <div>
                    <a class="nav-link" href="upload.php">Camera</a>
                    <div class="dropdown">
                        <button class="dropbtn"><?php if(isset($username)) echo $username; ?></button>
                        <div class="dropdown-content">
                            <a href="../authentication/edit.php">Edit Profile</a>
                            <a href="#">Private Gallery</a>
                            <a href="../resources/logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="container">
        </div>
    </body>
</html>