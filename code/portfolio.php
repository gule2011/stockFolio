<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheets/portfolio.css">
    </head>
    <body>
        
        <?php

        $user = $_SESSION["username"];
        $pass = $_SESSION["password"];

        $t = $_POST['tick'];
        $p = $_POST['price'];
        $q = $_POST['quan'];

        $_SESSION["numOf"] = 10;
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

        //Query username database
        $dbq = "SELECT id, username, password FROM Username WHERE username = '$user' AND password = '$pass'";
        
        $result = mysqli_query($conn, $dbq);

        while($row = mysqli_fetch_assoc($result))
        {
            $id1 = $_SESSION["id"] = $row["id"];
        }

        // sql to create table
            $sql2 = "CREATE TABLE Stocks (
            id INT(6),
            ticker1 VARCHAR(50),
            quantity1 VARCHAR(50),
            price1 VARCHAR(50),
            ticker2 VARCHAR(50),
            quantity2 VARCHAR(50),
            price2 VARCHAR(50),
            ticker3 VARCHAR(50),
            quantity3 VARCHAR(50),
            price3 VARCHAR(50),
            ticker4 VARCHAR(50),
            quantity4 VARCHAR(50),
            price4 VARCHAR(50),
            ticker5 VARCHAR(50),
            quantity5 VARCHAR(50),
            price5 VARCHAR(50),
            ticker6 VARCHAR(50),
            quantity6 VARCHAR(50),
            price6 VARCHAR(50),
            ticker7 VARCHAR(50),
            quantity7 VARCHAR(50),
            price7 VARCHAR(50),
            ticker8 VARCHAR(50),
            quantity8 VARCHAR(50),
            price8 VARCHAR(50),
            ticker9 VARCHAR(50),
            quantity9 VARCHAR(50),
            price9 VARCHAR(50),
            ticker10 VARCHAR(50),
            quantity10 VARCHAR(50),
            price10 VARCHAR(50),
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";

        if ($conn->query($sql2) === TRUE) 
        {
            //echo "Table Username created successfully";
        } 
        else 
        {
            //echo "Error creating table: " . $conn->error;
        }

        $id2 = 0;

        //query to check if the id exists yet
        $exists = "SELECT id FROM Stocks WHERE id = '$id1'";

        $result2 = mysqli_query($conn, $exists);
       
        while($row2 = mysqli_fetch_assoc($result2))
        {
            $id2 = $_SESSION["id"] = $row2["id"];
            //echo "<br> ID2: $id2";
        }

        if ($_POST['tick'] && $_POST['tick'] !== NULL)
        {   
            $_SESSION['ticknum'] ++;
            $ticknum = $_SESSION['ticknum']; 
            //insert ticker into table 
            if ($id2 == $id1)
            {
            }
            else
            {
                $sql3 = "INSERT INTO Stocks (id)
                VALUES ('$id1')";

                if ($conn->query($sql3) === TRUE) 
                {
                    //echo "Table Username created successfully";
                } 
                else 
                {
                    //echo "Error creating table: " . $conn->error;
                }
            }

            $addedt = "UPDATE Stocks
            SET ticker$ticknum = '$t'
            WHERE id = $id1";

            $addedq = "UPDATE Stocks
            SET quantity$ticknum = '$q'
            WHERE id = $id1";

            $addedp = "UPDATE Stocks
            SET price$ticknum = '$p'
            WHERE id = $id1";

        }
        else
        {
            //echo "ticker not posted";
        }

        if ($conn->query($addedt) === TRUE) {
            //echo "New record created successfully";
        } else {
            //echo "Error: " . $addedt . "<br>" . $conn->error;
        }

        if ($conn->query($addedq) === TRUE) {
            //echo "New record created successfully";
        } else {
            //echo "Error: " . $addedp . "<br>" . $conn->error;
        }

        if ($conn->query($addedp) === TRUE) {
            //echo "New record created successfully";
        } else {
            //echo "Error: " . $addedp . "<br>" . $conn->error;
        }
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
                        echo "$value <br>";
                        echo "PERCENTAGE GAINS: ";
                        echo "%$percent";

                    }

                }
            }
        }

        echo "<br><br><br>";
        echo "TOTAL GAINS: ";
        echo "%$totalpercent";
        echo "<br>";
        echo "TOTAL VALUE: ";
        echo "$$totalvalue";
        
        $conn->close();
        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method ="post">
            <div class="container">
                <h3>Add Stock</h3>
                <p>Add ticker and purchase price to add stocks to your portfolio.</p>
                <hr>

                <label for="ticker"><b>Stock</b></label>
                <input type="text" placeholder="Enter Ticker" name="tick" required style='text-transform:uppercase'/>

                <label for="quantity"><b>Stock</b></label>
                <input type="text" placeholder="Enter Quantity" name="quan" required>

                <label for="price"><b>Price</b></label>
                <input type="text" placeholder="Enter Puchase Price" name="price" required>

                <input type = "submit" name = "submit" value = "Submit">
            </div>
        </form>

        <p><a href="index.php">Log Out</a></p>


        
</body>
</html>