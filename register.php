<!-- Secures connection to database -->
<?php
    include 'dbConnection.php';
?>

<!-- HTML for registration -->
<html>
    <head>
        <title>Registration Page</title>
        <link rel="stylesheet" href="Login.css">
    </head>

    <body>

    <div class="login-box">
            <img src="roadhog.jpg" class="roadhog">
            <h2>Register</h2>
                <form action="register.php" method="POST">
                    <p>Username</p>
                    <input type="text" name="username" placeholder="Enter Username">
                    <p>Password</p>
                    <input type="password" name="password" placeholder="Enter Password">
                    <p>Confirm Password</p>
                    <input type="password" name="confirm-pass" placeholder="Confirm Password">
                    <br /><br/>
                    <input type="checkbox" name="Remember me" value="Remember me">Remember me  
                    <input type="submit" name="submit" value="register" id="submit-login">
                    <br />
                    Already have an account?
                    <a href="Login.php">Click here</a>
                </form>
    </div>     

    </body>
</html>

<!-- PHP for registration -->
<?php
    //Retrieve user info
    if(isset($_POST["submit"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm-pass"];
        //Check if password and confirm password strings match
        if (strcmp($password, $confirm_password) == 0) {
            //insert user info to database
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO user_info (id, username, password, reg_date)
                    VALUES (null, '$username', '$hashed_password', null)";
            print $query;
            //if user info could not be inserted, print error
            //otherwise, redirect to login page
            if ($database->query($query) === FALSE) {
                print "Oops! Something went wrong.";
            }
            else {
                print "Congratulations! You have successfully registered.";
                header("Location: Login.php");
            }
        }
        else {
            print "Passwords do not match";
        }
    }
?>