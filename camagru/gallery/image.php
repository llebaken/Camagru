<?php
    include_once '../resources/session.php';
    include_once '../config/setup.php';

    $imageId = $_GET['id'];
    $userid = $_SESSION['id'];
    $showDelete = 0;

    try{
     
        
        $query = "SELECT * FROM gallery WHERE id = :imageId";
        $statement = $conn->prepare($query);
        $statement->bindParam(':imageId', $imageId);
        $statement->execute();
        if($statement->rowCount() == 1){
            $row = $statement->fetch();
            
            $temp_userid = $row['userid'];
            if($temp_userid == $userid){
                $showDelete = 1;
            }
            $image = $row['image'];
            $imageuserid = $row['userid'];
            echo $image;
        }else{
        }
    }catch(PDOException $ex){
        echo "Error: ".$ex->message();
    }
    try{
        $query = "SELECT * FROM users WHERE id = :imageuserid";
        $statement = $conn->prepare($query);
        $statement->bindParam(':imageuserid', $imageuserid);
        $statement->execute();
        if($statement->rowCount() == 1){
            $row = $statement->fetch();
            $imageusername = $row['username'];
        }
    }catch(PDOException $ex){
        echo "Error: ".$ex->message();
    }

    try{
        $query = "SELECT * FROM users WHERE id = :userid";
        $statement = $conn->prepare($query);
        $statement->bindParam(':userid', $userid);
        $statement->execute();
        if($statement->rowCount() == 1){
            $row = $statement->fetch();
            $username = $row['username'];
        }
    }catch(PDOException $ex){
        echo "Error: ".$ex->message();
    }

    try{
        $query = "SELECT * FROM likes WHERE imageid = :imageid";
        $statement = $conn->prepare($query);
        $statement->bindParam(':imageid', $imageId);
        $statement->execute();
        $num_like = $statement->rowCount()." likes";
    }catch(PDOException $ex){
        echo "Error: ".$ex->message();
    }

    if(isset($_POST['delete'])){
        try
        {
            $imagepath = "photos/".$image;
            unlink($imagepath);
            $query = "DELETE FROM gallery WHERE id = :imageid";
            $statement = $conn->prepare($query);
            $statement->bindParam(':imageid', $imageId);
            $statement->execute();
            $query = "DELETE FROM likes WHERE imageid = :imageid";
            $statement = $conn->prepare($query);
            $statement->bindParam(':imageid', $imageId);
            $statement->execute();
            $query = "DELETE FROM comments WHERE imageid = :imageid";
            $statement = $conn->prepare($query);
            $statement->bindParam(':imageid', $imageId);
            $statement->execute();
            header("location: public.php");
        }
        catch(PDOException $ex)
        {
            $res = "<p>An error has occurred: ".$ex->getMessage()."</p>";
        }
    }

    if(isset($_POST['like'])){
        $query = "SELECT * FROM likes WHERE userid = :userid AND imageid = :imageid";
        $statement = $conn->prepare($query);
        $statement->bindParam(':userid', $userid);
        $statement->bindParam(':imageid', $imageId);
        $statement->execute();
        if($statement->rowCount() == 0)
        {
            try
            {
                $query = "INSERT INTO likes(userid, imageid)
                        VALUES (:userid, :imageid)";
                $statement = $conn->prepare($query);
                $statement->bindParam(':userid', $userid);
                $statement->bindParam(':imageid', $imageId);
                $statement->execute();
                if($statement->rowCount() == 1)
                {
                    $query = "SELECT * FROM likes WHERE imageid = :imageid";
                    $statement = $conn->prepare($query);
                    $statement->execute(array(':imageid'=> $imageId));
                    $num_like = $statement->rowCount()." likes";
                }
            }
            catch(PDOException $ex)
            {
                $res = "<p>An error has occurred: ".$ex->getMessage()."</p>";
            }
        }
        else
        {
            try
            {
                $query = "DELETE FROM likes WHERE userid = :userid AND imageid = :imageid";
                $statement = $conn->prepare($query);
                $statement->bindParam(':userid', $userid);
                $statement->bindParam(':imageid', $imageId);
                $statement->execute();
                $query = "SELECT * FROM likes WHERE imageid = :imageid";
                $statement = $conn->prepare($query);
                $statement->execute(array(':imageid'=> $imageId));
                $num_like = $statement->rowCount()." likes";
            }
            catch(PDOException $ex)
            {
                $res = "<p>An error has occurred: ".$ex->getMessage()."</p>";
            }
        }


    }

    if(isset($_POST['comment'])){
        if(isset($_POST['commenttxt']) && $_POST['commenttxt'] != NULL)
        {
            $comment = $_POST['commenttxt'];
            try
            {
                $query = "INSERT INTO comments(userid, imageid, comment)
                        VALUES (:userid, :imageid, :comment)";
                $statement = $conn->prepare($query);
                $statement->bindParam(':userid', $userid);
                $statement->bindParam(':imageid', $imageId);
                $statement->bindParam(':comment', $comment);
                $statement->execute();
                if($statement->rowCount() == 1)
                {
                    $res = "<p>Comment updated</p>"; 
                }
            }
            catch(PDOException $ex)
            {
                $res = "<p>An error has occurred: ".$ex->getMessage()."</p>";
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
                <a class="navbar-brand" id="icon" href="public.php">Camagru</a>
                <div>
                    <a class="nav-link" href="upload.php">Camera</a>
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
            <div class="image">
                <div class="row">
                    <div class="col">
                        <?php
                            if(isset($imageusername)){
                                echo $imageusername;
                            }
                        ?>
                    </div>
                    <div class="col">
                        <?php
                            if($showDelete == 1){
                                echo "<form method='post'>";
                                    echo "<input type='submit' value='Delete' name='delete' class='btn btn-primary'>";
                                echo "</form>";     
                            }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <img width='500' height='375' src="photos/<?php echo $image ?>">
                </div>
                <div class="row">
                    <form method="post">
                        <input type="submit" value="Like" name="like" class="btn btn-primary">
                        <?php
                           if(isset($num_like)){
                                echo $num_like;
                            } 
                        ?>
                    </form>
                </div>
                <div class="row">
                    <form method="post">
                        <div>
                            <textarea rows="4" cols="50" name="commenttxt" placeholder="Enter text here..."></textarea>
                        </div>
                        <input type="submit" value="Leave Comment" name="comment" class="btn btn-primary">
                    </form>
                </div>
                <div class="row">
                    <?php
                        $query = "SELECT * FROM comments WHERE imageid = :imageid";
                        $statement = $conn->prepare($query);
                        $statement->bindParam(':imageid', $imageId);
                        $statement->execute();
                        if($statement->rowCount() > 0){
                            while($row = $statement->fetch()){
                                $comment = $row['comment'];
                                $comment_userid = $row['userid'];
                                $query = "SELECT * FROM users WHERE id = :comment_userid";
                                $stat = $conn->prepare($query);
                                $stat->bindParam(':comment_userid', $comment_userid);
                                $stat->execute();
                                if($stat->rowCount() == 1){
                                    $row2 = $stat->fetch();
                                    $comment_username = $row2['username'];
                                    echo "<div>";
                                        echo "<div";
                                            echo "<p><b>$comment_username</b></p>";
                                            echo "<p>$comment</p>";
                                        echo "</div>";
                                    echo "</div>";
                                }
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
