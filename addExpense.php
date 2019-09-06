<?php 
    include 'dbConnection.php'; 
    include 'LoginBackend.php';
    session_start();
    $categories = array("Mortgage", "Public Utilities", "Car", "Insurance", "Gifts", "Clothes", "Health Expenses", "Other");
    $userID = $_SESSION['id'];
    if (isset($_POST["categoryList"])) {
        $category = $_POST["categoryList"];
        if ($category == "Categories") {
            //If default category is selected, it means the user has not chosen a category to add to list
            print "Please select a category";
        }
        else {
            //add to my category list and save to database new user category list
            if (isset($_POST["add_to_spent"])) {
                $expense = $_POST["money_spent"];
                $insertToCategories = "INSERT INTO categories (id, category_name, money_spent, exp_date, curr_date) VALUES ('$userID', '$category', '$expense', null, CURDATE())";
                //print "user ID is " . $userID . ", category is " . $category . ", expense is " . $expense . " ";
                //check if data is inserted to categories table
                if (mysqli_query($database, $insertToCategories)) {
                    //print "Today's expense has successfully been updated; ";
                    //now update Today's Total Spent section
                    //gets the sum of today's total spent for the user for current date
                    $getTotalSpent = "SELECT SUM(money_spent) AS exp_sum FROM categories WHERE id='$userID' AND curr_date=CURDATE()";
                    $totalSpent = mysqli_query($database, $getTotalSpent);
                    $row = mysqli_fetch_assoc($totalSpent);
                    $moneySpent = $row['exp_sum'];
                    //the user spent zero dollars today, telling us either (1) the user is new, or (2) it's a new day for user and we need to insert new total spent row for user
                    //this can actually be solved with one statement without an if/else because both require us to insert new row to current_expense
                    if (mysqli_num_rows(mysqli_query($database, "SELECT id FROM current_expense WHERE id='$userID' AND curr_date=CURDATE()")) < 1) {
                        mysqli_query($database, "INSERT INTO current_expense (id, total_spent, exp_date, curr_date) VALUES ('$userID', '$expense', null, CURDATE())");
                        print "New row inserted for user with current date";
                    }
                    else {
                        mysqli_query($database, "UPDATE current_expense SET total_spent='$moneySpent' WHERE id='$userID'");
                        print "Data has successfully been updated";
                    }
                    //Now I must deduct this expense amount from my balance
                    $getUserBalanceArr = mysqli_fetch_assoc(mysqli_query($database, "SELECT balance FROM user_balance WHERE id='$userID'"));
                    $userBalance = $getUserBalanceArr['balance'];
                    $newBalance = money_format('%.2n', ($userBalance - $expense));
                    mysqli_query($database, "UPDATE user_balance SET balance='$newBalance' WHERE id='$userID'");
                } else {
                    print "Unable to insert to categories table";
                }
            } 
            else {
                print "It did not go through the add";
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Expense</title>
        <link rel="stylesheet" href="Login.css">
    </head>

    <body>

        <h2>Select Category</h2>
        <form action="addExpense.php" method="POST">
            <select name="categoryList">
                <option selected="selected">Categories</option>
                <option name="mortgage" value="Mortgage">Mortgage</option>
                <option name="public_utilities" value="Public Utility">Public Utility</option>
                <option name="car" value="Car">Car</option>
                <option name="insurance" value="Insurance">Insurance</option>
                <option name="gifts" value="Gifts">Gifts</option>
                <option name="clothes" value="Clothes">Clothes</option>
                <option name="health_expenses" value="Health Expenses">Health Expenses</option>
                <option name="other" value="Other">Other</option>
            </select>

        <h2>Amount Spent</h2>
            $<input type="number" name="money_spent" placeholder="0.00">
            <input type="submit" name="add_to_spent" value="Add">
        </form>

        <?php
            $theTotal = "SELECT total_spent FROM current_expense WHERE id='$userID' AND curr_date=CURDATE()";
            $theTotali = mysqli_query($database, $theTotal);
            if (mysqli_num_rows($theTotali) > 0) {
                $todaysTotal = mysqli_fetch_assoc($theTotali);
                $displayTotal = $todaysTotal["total_spent"];
            }
        ?>

        <h2>Today's Total Spent</h2>
        <input type="text" name="total_spent" value="<?php print money_format('%.2n', $displayTotal) ?>" readonly>
        <a href="expenseGraph.php">See Graph</a>
        <br/>
        <a href="home.php">Home</a>

        <!-- <h2>Edit Today's Total Spent</h2>
        <input type="text" name="edit_expense" placeholder="$0.00">
        <input type="submit" name="edit" value="Edit"> -->

        <br/>
        <a href="logout.php">Log out</a>
        <br/>

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="addExpense.js"></script>

    </body>
</html>