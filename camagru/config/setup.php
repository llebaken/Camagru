<?php
	include_once 'database.php';
	
	try {
        $conn = new PDO("mysql:host=$servername", $USER, $PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE IF NOT EXISTS camagru";
        $conn->exec($sql);
        echo "Database created<br>";
	}catch (PDOException $ex) {
	    echo $sql . "<br>" . $ex->getMessage();
    }
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $USER, $PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "USE camagru";
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT (6) AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            confirm INT(11) NOT NULL,
            confirmcode INT(11) NOT NULL,
            notify INT(6) NOT NULL,
            join_date TIMESTAMP
        )";
        $conn->exec($sql);
        echo "users table created<br>";
    }catch(PDOException $ex) {
        echo $sql . "<br>" . $ex->getMessage();
    }

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $USER, $PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "USE camagru";
        $sql = "CREATE TABLE IF NOT EXISTS gallery (
            id INT (6) AUTO_INCREMENT PRIMARY KEY,
            userid INT (6) NOT NULL,
            image VARCHAR(255) NOT NULL
        )";
        $conn->exec($sql);
        echo "Gallery table created<br>";
    }catch(PDOException $ex) {
        echo $sql . "<br>" . $ex->getMessage();
    }
?>