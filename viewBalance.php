<?php
    include 'dbConnection.php';
    include 'LoginBackend.php';
    session_start();
    $userID = $_SESSION['id'];

    $strUserBalance = "SELECT balance FROM user_balance WHERE id='$userID'";
    $balanceArr = mysqli_fetch_assoc(mysqli_query($database, $strUserBalance));
    $balance = $balanceArr['balance'];

?>

<!DOCTYPE html>
<html>

    <head>
        <title>View My Balance</title>
        <link rel="stylesheet" href="Login.css">
    </head>

    <body>
        <h2>My Balance</h2>
        <!-- This value should change based on user_balance table's column "balance" -->
        $<input type="text" name="my_balance" value="<?php print $balance ?>" readonly>

        <br/>
        <a href="home.php">Home</a>
        <br/>
        <a href="logout.php">Log out</a>
    </body>

</html>