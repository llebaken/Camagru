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

    if(isset($_POST['uploadlocal'])){
        $target = "photos/" .basename($_FILES['image']['name']);
        $image = $_FILES['image']['name'];
        $userid = $_SESSION['id'];

        if(isset($image)){
            try{
                $query = "INSERT INTO gallery (userid, image) VALUES (:userid, :image)";
                $statement = $conn->prepare($query);
                $statement->bindParam(':userid', $userid);
                $statement->bindParam(':image', $image);
                $statement->execute();

                if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
                    $msg = "Image uploaded";
                }else{
                    $msg = "There was a error uploading the image";
                }
            }catch(PDOException $ex){
                $msg = $ex->getMessage();
            }
        }else{
            $msg = "No image";
        }
    }
?>

<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="../resources/css/gallery.css">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="header">
            <nav class="navbar">
                <a class="navbar-brand" id="icon" href="public.php">Camagru</a>
                <div>
                    <a class="nav-link" href="#">Camera</a>
                    <div class="dropdown">
                        <button class="dropbtn"><?php if(isset($username)) echo $username; ?></button>
                        <div class="dropdown-content">
                            <a href="../authentication/edit.php">Edit Profile</a>
                            <a href="private.php">Private Gallery</a>
                            <a href="../resources/logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="container">
        <?php if(isset($msg)) echo $msg; ?>
            <div class="row">
                <div class="col">
                    <div class="row">
                        <button type="button" width="400" data-toggle="modal" data-target="#exampleModal">Upload Photo</button>
                    </div>
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Upload Local Image</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="post" enctype="multipart/form-data">
                                    <div>
                                        <input type="file" name="image">
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <input type="submit" name="uploadlocal" value="upload image">
                                    </div>
                                </form>
                            </div>
                            <!-- <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <!-- Grab stream from camera -->
                         <video id="video" width="400" height="300"></video>
                    </div>
                    <div class="row">
                        <button id="capture">Take Image</button>
                    </div>
                    <div class="row">
                        <canvas id="canvas" width="400" height="300"></canvas>
                    </div>
                    <div class="row" >
                        <button>Save Image</button>
                    </div>
                </div>
                <div class="col">
                    <!-- Overlays -->
                    <div class="row">
                    </div>
                    <div class="row">
                    </div>
                    <div class="row">
                    </div>
                    <div class="row">
                    </div>
                </div>
            </div>
        </div>
        <script src="../resources/js/photo.js"></script>
    </body>
</html>