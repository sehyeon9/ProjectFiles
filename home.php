<!DOCTYPE html>
<html>
    <head>
        <title>Home Page</title>
        <link rel="stylesheet" href="Login.css">
    </head>

    <body>
    <div class="top_right_link">
        <a href="Login.php">Log out</a>
    </div>

    <div class="center">
        <h1>Home</h1>
        <form action="home.php" method="POST">
                <input class="button_format" type="submit" name="add_expense" value="Add Expense">
                <br/>
                <input class="button_format" type="submit" name="view_expense" value="View Expense">
                <br/>
                <input class="button_format" type="submit" name="add_income" value="Add Income">
                <br/>
                <input class="button_format" type="submit" name="view_balance" value="View Balance">
        </form>
    </div>

    </body>

</html>

<?php include 'dbConnection.php';
    if (isset($_POST["add_expense"])) {
        header("Location: addExpense.php");
    } else if (isset($_POST["view_expense"])) {
        header("Location: viewExpense.php");
    } else if (isset($_POST["add_income"])) {
        header("Location: addIncome.php");
    } else if (isset($_POST["view_balance"])) {
        header("Location: viewBalance.php");
    }
?>