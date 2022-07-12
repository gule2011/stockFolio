<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="stylesheets/index.css">
    </head>
    <body>
        
        <h2>StockFolio</h2>

        <form action="secondHome.php" method ="post">
            <div class="container">
                <h3>Log In</h3>
                <p>Enter details below to login.</p>
                <hr>

                <label for="username"><b>Username</b></label>
                <input type="text" placeholder="Enter Username" name="username" required>

                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="psw" required>

                <input type = "submit" name = "submit" value = "Login">
            </div>
        </form>

        
        <p><a href="register.php">Register</a></p>

</body>
</html>
