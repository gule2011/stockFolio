<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheets/secondHome.css">
    </head>
    <body>
        
        <h2>StockFolio</h2>

        <?php

            $_SESSION['ticknum'] = 0;
            $ticknum = $_SESSION['ticknum'];

            $_SESSION["numOf"] = 6;
            $numOf = $_SESSION["numOf"];

            $totalpercent = 0;
            $totalvalue = 0;

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

            $_SESSION["username"] = ($_POST['username']);
            $_SESSION['password'] = ($_POST['psw']);

            $user = $_SESSION["username"];
            $pass = $_SESSION["password"];

            $dbq = "SELECT id, username, password FROM Username WHERE username = '$user' AND password = '$pass'";
        
            $result = mysqli_query($conn, $dbq);
    
            while($row = mysqli_fetch_assoc($result))
            {
                $id1 = $_SESSION["id"] = $row["id"];
            }

            //check is $_POST contains username`
            ?>
            <h3>Portfolio Holdings</h3>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                for ($one = 1; $one <= $numOf; $one++)
                {
                    //echo "one is up to: $one <br>";
                    $sofar = "SELECT ticker$one, quantity$one, price$one FROM Stocks WHERE id = '$id1'";
    
                    $result3 = mysqli_query($conn, $sofar);
    
                    while($row3 = mysqli_fetch_assoc($result3))
                    {
                        $displayt = $row3["ticker$one"];
                        $displayq = $row3["quantity$one"];
                        $displayp = $row3["price$one"];
    
                        if ($displayt != NULL)
                        {
                            $quote = $displayt;
    
                            //API REQUEST FOR STOCK PRICE
                            require_once 'unirest-php-master/src/Unirest.php';   // here we add path of Unirest.php 
        
                            $response = Unirest\Request::get("https://flipvo.p.rapidapi.com/api/rapid/company/quote/$quote",
                            array(
                            "X-Mashape-Key" => "dbd057b363msh380770b52462ae4p154c45jsn5a6a0e4402dd",
                            "X-Mashape-Host" => "flipvo.p.rapidapi.com"
                            )
                            );
                                        
                            echo "<pre>";
                                        
                            $stuff =  json_decode($response->raw_body);
                            $quoteprice = $stuff[0]->price;
    
                            //calculations required
                            $percent = ($quoteprice - $displayp)/$displayp * 100;
                            $totalpercent = $totalpercent + $percent;

                            $value = $displayq * $displayp;
                            $totalvalue = $totalvalue + $value;


                            //Display portfolio below
                            echo "TICKER: ";
                            echo "$displayt<br>";
                            echo "QUANTITY: ";
                            echo "$displayq <br>";
                            echo "PRICE PURCHASED: ";
                            echo "$$displayp<br>";
                            echo "CURRENT PRICE: ";
                            echo "$$quoteprice <br>";
                            echo "TOTAL VALUE: ";
                            echo "$totalvalue <br>";
                            echo "PERCENTAGE GAINS: ";
                            echo "%$percent";
    
                        }
    
                    }
                }
            }
            
            echo "<br><br><br>";
            echo "TOTAL GAINS: ";
            echo "<span class='totalpercent'>"."%$totalpercent". "</span>";
            echo "<br>";
            echo "TOTAL VALUE: ";
            echo "$$totalvalue";

            $conn->close();
        ?>
 
        <form action="portfolio.php" method ="post">
            <div class="container">
                <h3>Add Stock</h3>
                <p>Add ticker and purchase price to add stocks to your portfolio.</p>
                <hr>

                <label for="ticker"><b>Stock</b></label>
                <input type="text" placeholder="Enter Ticker" name="tick" required/>

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