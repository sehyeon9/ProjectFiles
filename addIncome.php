<?php
    include 'dbConnection.php';
    include 'LoginBackend.php';
    session_start();
    $userID = $_SESSION['id'];

    //Add income automatically
    if (isset($_POST["add_auto"])) {
        if (isset($_POST['timeperiod'])) {
            $timePeriod = $_POST['timeperiod'];
            print "time period is: " . $timePeriod . "; ";
            if (isset($_POST['date'])) {
                $payday = $_POST['date'];
                print "payday is: " . $payday . "; ";
                $income = money_format('%.2n', $_POST['auto_income']);
                //if no data exists for user in user_income table, insert into user_balance table with balance equal to income
                //otherwise, insert into user_income the same fields BUT update the user_balance table with oldBalance added with this income
                //if user does not exist in user_income, it also means user does not exist in user_balance
                $strFindUser = "SELECT * FROM user_income WHERE id='$userID'";
                if (mysqli_num_rows(mysqli_query($database, $strFindUser)) == 0) {
                    $strIncome = "INSERT INTO user_income (id, income, automatic, period, income_date) VALUES ('$userID', '$income', TRUE, '$timePeriod', '$payday')";
                    $queryIncome = mysqli_query($database, $strIncome);
                    $strBalance = "INSERT INTO user_balance (id, balance, curr_date) VALUES ('$userID', '$income', CURDATE())";
                    mysqli_query($database, $strBalance);
                    print "Data has successfully been CREATED";
                }
                else {
                    $strAdditionalIncome = "INSERT INTO user_income (id, income, automatic, period, income_date) VALUES ('$userID', '$income', TRUE, '$timePeriod', '$payday')";
                    $queryAddIncome = mysqli_query($database, $strAdditionalIncome);
                    $getOldBalance = mysqli_fetch_assoc(mysqli_query($database, "SELECT balance FROM user_balance WHERE id='$userID'"));
                    $userOldBalance = $getOldBalance['balance'];
                    $userNewBalance = money_format('%.2n', ($userOldBalance + $income));
                    $strUpdBalance = "UPDATE user_balance SET balance='$userNewBalance' WHERE id='$userID'";
                    mysqli_query($database, $strUpdBalance);
                    print "Data has successfully been UPDATED";
                }
                print "income is: " . $income . "; ";
            } else {
                print "Date must be set";
            }
        } else {
            print "You must choose one among \"By Day\", \"Weekly\", and \"Yearly\"";
        }
    }

    //Add income manually
    if (isset($_POST["add_manual"])) {
        if (isset($_POST['manual_income'])) {
            $manualIncome = money_format('%.2n', $_POST['manual_income']);
            $strSearchUser = "SELECT * FROM user_balance WHERE id='$userID'";
            if (mysqli_num_rows(mysqli_query($database, $strSearchUser)) == 0) {
                mysqli_query($database, "INSERT INTO user_balance (id, balance, curr_date) VALUES ('$userID', '$manualIncome', CURDATE())");
            } else {
                $obtainOldBalanceArr = mysqli_fetch_assoc(mysqli_query($database, "SELECT balance FROM user_balance WHERE id='$userID'"));
                $obtainOldBalance = $obtainOldBalanceArr['balance'];
                $newBalance = money_format('%.2n', ($obtainOldBalance + $manualIncome));
                mysqli_query($database, "UPDATE user_balance SET balance='$newBalance' WHERE id='$userID'");
            }
        } else {
            print "Manual income input is empty!";
        }
    }

    //Edit automated income
    if (isset($_POST["update_income"])) {
        //old data
        $oldIncome = money_format('%.2n', $_POST['old_income']);
        $oldPeriod = $_POST['old_period'];
        $oldDate = $_POST['old_date'];
        //new data
        $newIncome = money_format('%.2n', $_POST['new_income']);
        $newPeriod = $_POST['new_period'];
        $newDate = $_POST['new_date'];
        //First check if old data user entered matches old data stored in database
        $strOldData = "SELECT * FROM user_income WHERE id='$userID' AND income='$oldIncome' AND period='$oldPeriod' AND income_date='$oldDate'";
        if (mysqli_num_rows(mysqli_query($database, $strOldData)) == 0) {
            print "Cannot find select income/period/date from income list";
        } else {
            //Need to check if user inputted "Same" for any of the fields because the ones marked "Same" do not get updated
            $strNewData = "UPDATE user_income SET income='$newIncome', period='$newPeriod', income_date='$newDate' WHERE id='$userID' AND income='$oldIncome' AND period='$oldPeriod' AND income_date='$oldDate'";
            mysqli_query($database, $strNewData);
        }
    }

?>

<!DOCTYPE html>
<html>

    <head>
        <title>Add Income</title>
        <link rel="stylesheet" href="addIncome.css">
    </head>

    <body>

        <form action="addIncome.php" method="POST">
            <h2>Add Income Automatically</h2>
            <input type="radio" name="timeperiod" value="By Day">By Day
            <input type="radio" name="timeperiod" value="Week">Weekly
            <input type="radio" name="timeperiod" value="Month">Monthly
            <br/>

            Choose a date<input type="date" name="date">
            <br/>

            $<input type="text" name="auto_income" placeholder="0.00">
            <input type="submit" name="add_auto" value="Add">
            <br/>

            <!-- OnClick activate JS to have a pop-up that allows users to select the income row in the table
                and edit the amount, period, or date in the row  -->
            <input type="submit" name="display_table" value="Pull up my income list">
            <br/>
            
            <table border="1">
                <!-- Displays user's income list as a table -->
                <?php
                    if (isset($_POST['display_table'])) {
                        $getIncomeList = "SELECT income, period, income_date FROM user_income WHERE id='$userID'";
                        $incomeList = mysqli_query($database, $getIncomeList);
                        echo "<tr><td>Income</td><td>Period</td><td>Pay Day</td></tr>";
                        while ($row = $incomeList->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['income'] . "</td>";
                            echo "<td>" . $row['period'] . "</td>";
                            echo "<td>" . $row['income_date'] . "</td>";
                            echo "</tr>";
                        }
                    }
                ?>

            </table>
            <br/>

            <h3>Edit My Automated Income</h3>
            <div class="past_income">
                <label for="income_history">For income:</label>
                <input type="text" name="old_income" id="income_history">
                <label for="period_history">period:</label>
                <input type="text" name="old_period" id="period_history">
                <label for="date_history>">date:</label>
                <input type="text" name="old_date" id="date_history">
            </div>
            <p>If a field does not change, please enter "Same"</p>
            <div class="new_income">
                <label for="edited_income">Change to income:</label>
                <input type="text" name="new_income" id="edited_income">
                <label for="edited_period">period:</label>
                <input type="text" name="new_period" id="edited_period">
                <label for="edited_date">date:</label>
                <input type="text" name="new_date" id="edited_date">
            </div>
            <input type="submit" name="update_income" value="Change">

            <h2>Add Income Manually</h2>
            $<input type="text" name="manual_income" placeholder="0.00">
            <input type="submit" name="add_manual" value="Add">
            <br/>
        </form>

        <a href="home.php">Home</a>
        <br/>
        <a href="logout.php">Log out</a>
    </body>

</html>