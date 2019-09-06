This file stores all codes that work but are undergoing changes. In case the actual file breaks the code, copy and paste from here.

This is for addExpense.php:

 <?php   //add to my category list and save to database new user category list
    if (isset($_POST["add_to_spent"])) {
        $expense = $_POST["money_spent"];
        $insertToCategories = "INSERT INTO categories (id, category_name, money_spent, exp_date, curr_date) VALUES ('$userID', '$category', '$expense', null, CURDATE())";
        //print "user ID is " . $userID . ", category is " . $category . ", expense is " . $expense . " ";
        //check if data is inserted to categories table
        if (mysqli_query($database, $insertToCategories)) {
            //print "Today's expense has successfully been updated; ";
            //now update Today's Total Spent section
            $getTotalSpent = "SELECT SUM(money_spent) AS exp_sum FROM categories WHERE id='$userID'";
            $totalSpent = mysqli_query($database, $getTotalSpent);
            $row = mysqli_fetch_assoc($totalSpent);
            $moneySpent = $row['exp_sum'];
            //if user does not have total spent data we need to insert, else we need to update
            $query = "SELECT id FROM current_expense WHERE id='$userID'";
            if (mysqli_num_rows(mysqli_query($database, $query)) != 1) {
                //print "Data has successfully been created";
                mysqli_query($database, "INSERT INTO current_expense (id, total_spent, exp_date, curr_date) VALUES ('$userID', '$moneySpent', null, CURDATE())");
            } else {
                //if curr_date differs in categories compared to current_expense we must insert new row
                //otherwise we must update the current row for current date
                //This is the current date which was recently stored into categories
                $getCurrDate = "SELECT curr_date FROM categories WHERE id='$userID'";
                $currDateArray = mysqli_query($database, $getCurrDate);
                $recentDate = end(mysqli_fetch_assoc($currDateArray));
                //This is the current date which was previously (recently) stored into current_expense
                $getPrevDate = "SELECT curr_date FROM current_expense WHERE id='$userID'";
                $prevDateArray = mysqli_query($database, $getPrevDate);
                $prevDate = end(mysqli_fetch_assoc($prevDateArray));
                //This compares the curr_date between categories table and current_expense table
                if ($recentDate != $prevDate) {
                    mysqli_query($database, "INSERT INTO current_expense (id, total_spent, exp_date, curr_date) VALUES ('$userID', '$expense', null, CURDATE())");
                } else {
                    // print "categories\' date: " . $recentDate . "; ";
                    // print "previous date: " . $prevDate;
                    mysqli_query($database, "UPDATE current_expense SET total_spent='$moneySpent' WHERE id='$userID'");
                }
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
?>