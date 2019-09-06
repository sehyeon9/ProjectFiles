<?php
    if (isset($_POST["login"])) {
        session_start();
        $username = $_POST["username"];
        $password = $_POST["password"];
        $query = "SELECT id, password FROM user_info WHERE username ='$username'";
        $row = mysqli_query($database, $query);
        if (mysqli_num_rows($row) > 0) {
            $data = mysqli_fetch_array($row, MYSQLI_ASSOC);
            $_SESSION["id"] = $data['id'];
            if (password_verify($password, $data['password'])) {
                header("Location: home.php");
            }
        }
        else {
            print "wrong password";
        }
    }
    
    if (isset($_POST["new"])) {
        header("Location: register.php");
    }
    
?>