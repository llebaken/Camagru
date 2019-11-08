<?php
    include_once '../session.php';
    include_once '../../config/setup.php';
    
    if(!isset($_SESSION['id'])){
        header("../index.php");
    }

    $userid = $_SESSION['id'];

    try{
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':id', $userid);
        $statement->execute();
        if($statement->rowCount() == 1){
            $row = $statement->fetch();
            $username = $row['username'];
            echo $username;
        }
    }catch(PDOException $ex){
        $error = $ex->getMessage();
    }

    //information from post 
    $layer1 = $_POST['baseimage'];
    $layer2 = $_POST['overlayimage'];

    // make image file for base image
    if(!empty($layer1) && !empty($username)){
        $baseimage = $username.time().".png";
        $imagepath = "../../gallery/photos/".$baseimage;
        $imgurl = str_replace("data:image/png;base64,", "", $layer1);
        $imageurl = str_replace(" ", "+", $imgurl);
        $imgdecode = base64_decode($imageurl);
        file_put_contents($imagepath, $imgdecode);

        // make image file for overlay
        if(isset($layer2)){
            $overlayimage = "overlay".time().".png";
            $overlaypath = "../../gallery/photos/".$overlayimage;
            $imgurl = str_replace("data:image/png;base64,", "", $layer2);
            $imageurl = str_replace(" ", "+", $imgurl);
            $imgdecode = base64_decode($imageurl);
            file_put_contents($overlaypath, $imgdecode);
        }

        // merge images
        if(isset($layer1) && isset($layer2)){
            $dest = imagecreatefrompng($imagepath);
            $src = imagecreatefrompng($overlaypath);

            imagecopymerge($dest, $src, 10, 9, 0, 0, 100, 100, 100); //have to play with these numbers for it to work for you, etc.
            imagepng($dest, $imagepath);

            imagedestroy($dest);
            imagedestroy($src);
            unlink($overlaypath);
        }

        //save image path in database
        try{
            $query = "INSERT INTO gallery (userid, image) VALUES (:userid, :image)";
            $statement = $conn->prepare($query);
            $statement->bindParam(':userid', $userid);
            $statement->bindParam(':image', $baseimage);
            $statement->execute();
        }catch(PDOException $ex){
            $msg = $ex->getMessage();
        }
    }

    return(0);
?>