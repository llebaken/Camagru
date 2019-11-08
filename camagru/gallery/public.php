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
                <a class="navbar-brand" id="icon" href="#">Camagru</a>
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
            <?php
                try{
                    //Get Number of rows in table
                    $query = "SELECT * FROM gallery";
                    $statement = $conn->prepare($query);
                    $statement->execute();
                    $number_of_results = $statement->rowCount();
                    //Set or Get Current Page
                    if(!isset($_GET['page'])){
                        $page = 1;
                    }else{
                        if (is_numeric($_GET['page'])) {
                            $page = $_GET['page'];
                        }
                        else{
                            $page = 1;
                        }
                        // $page = $_GET['page'];
                    }
                    //How many results you want per page
                    $result_per_page = 5;
                    //Total number of pages
                    $number_of_pages = ceil($number_of_results / $result_per_page);
                    //start limit
                    $start_limit_number = ($page - 1) * $result_per_page;
                //    $query = "SELECT * FROM gallery ORDER BY id DESC LIMIT :start_limit, :result_per_page";
                    $query = "SELECT * FROM gallery ORDER BY id DESC LIMIT $start_limit_number, $result_per_page";
                    $statement = $conn->prepare($query);
                   // $statement->bindParam(':start_limit', $start_limit_number);
                    //$statement->bindParam(':result_per_page', $result_per_page);
                    $statement->execute();
                    while($row = $statement->fetch()){
                        echo "<div onclick=location.href='image.php?id=".$row['id']."'>";
                            echo "<img width='500' height='375' src='photos/".$row['image']."'>";
                        echo "</div>";
                    }
                    for($page = 1; $page <= $number_of_pages; $page++){
                        echo "<a href= 'public.php?page=" .$page . "'>" . $page . "</a>";
                    }
                }catch(PDOException $ex) {
                    echo $sql . "<br>" . $ex->getMessage();
                }

            ?>
        </div>
    </body>
</html>