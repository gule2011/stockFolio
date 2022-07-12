<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheets/home.css">
    </head>
    <body>
        
        <?php

            $_SESSION['ticknum'] = 0;
            $ticknum = $_SESSION['ticknum'];

            $servername = "database-1.clf0kbhnpnnr.us-east-1.rds.amazonaws.com";
            $username = "admin";
            $password = "password";
            $dbname = "ebdb";
    
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

             // sql to create table
             $sql = "CREATE TABLE Username (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50),
            password VARCHAR(50),
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
        
            if ($conn->query($sql) === TRUE) {
                //echo "Table Username created successfully";
            } else {
                //echo "Error creating table: " . $conn->error;
            }

            $_SESSION["username"] = ($_POST['username']);
            $_SESSION['password'] = ($_POST['psw']);

            $user = $_SESSION["username"];

            //check is $_POST contains username
            if ($_POST['username'] && $_POST['username'] !== NULL) 
            {
                $user = ($_POST['username']);
                $pass = ($_POST['psw']);
                //insert form values to database
                $sql = "INSERT INTO Username (username, password)
                VALUES ('$user', '$pass')";
            } 
             else
             {
                //echo "failed";
             }

             //Check if values added to database
             if ($conn->query($sql) === TRUE) {
                //echo "New record created successfully";
            } else {
                //echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $conn->close();
        ?>
 
        <h3>Add A Stock</h3>
        
        <form action="portfolio.php" method ="post">
            <div class="container">
                <p>Add ticker and purchase price to add stocks to your portfolio.</p>
                <hr>

                <label for="ticker"><b>Stock</b></label>
                <input type="text" placeholder="Enter Ticker" name="tick" required>

                <label for="quantity"><b>Quantity</b></label>
                <input type="text" placeholder="Enter Quantity" name="quan" required>

                <label for="price"><b>Price</b></label>
                <input type="text" placeholder="Enter Puchase Price" name="price" required>

                <input type = "submit" name = "submit" value = "Submit">
            </div>
        </form>
        
        <p><a href="index.php">Log Out</a></p>

</body>
</html>