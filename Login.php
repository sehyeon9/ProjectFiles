<!-- HTML for login -->
<!DOCTYPE html>
<html>
    <head>
        <title>
            Login BOX
        </title>
        <link rel="stylesheet" href="Login.css">
    </head>
    
    <body>
        <div class="login-box">
            <img src="roadhog.jpg" class="roadhog">
            <h2>Login</h2>
                <form action="Login.php" method="POST">
                    <p>Username</p>
                    <input type="text" name="username" placeholder="Enter Username">
                    <p>Password</p>
                    <input type="password" name="password" placeholder="Enter Password">
                    <br /><br/>
                    <input type="checkbox" name="save" value="Remember me">Remember me  
                    <input type="submit" name="login" value="login" id="submit-login">
                    <br/><br/>
                    <input type="submit" name="new" value="Create new account" id="sign_up">
                </form>
        </div>     

    </body>
</html>

<!-- PHP for login -->
<?php 
    include 'dbConnection.php';
    include 'LoginBackend.php';
?>