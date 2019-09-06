<?php 
    include 'dbConnection.php';
    include 'LoginBackend.php';
    session_start();
    $userID = $_SESSION['id'];
    if (isset($_POST["output"])) {
        $decision = $_POST["decision"];
        $list = $_POST["list"];
        $subList = $_POST["subList"];

        //A constant that ensures the selected option from "View Expense" section is the year the user chose
        //If you wish to edit the available (html) options, you MUST edit this constant accordingly as well
        //If 2019 was the second option the user could choose, it would be 2017 + 2(nd option) resulting in 2019
        define ("YEAR_ADJUSTMENT_FACTOR", 2017);

        if ($decision != "Choose one" && $list != "Choose one" && $subList != 0) {
            if ($decision == "Average") {
                //CHOSEN YEAR AVERAGE EXPENSE
                if ($list == "Year") {
                    $chosenYear = YEAR_ADJUSTMENT_FACTOR + $subList;
                    $select = "SELECT AVG(money_spent) AS avg_sum FROM categories WHERE id='$userID' AND YEAR(curr_date) = '$chosenYear'";
                }
                //CHOSEN MONTH AVERAGE EXPENSE
                else if ($list == "Month") {
                    $select = "SELECT AVG(total_spent) AS avg_sum FROM current_expense WHERE id='$userID' AND MONTH(curr_date) = '$subList'";
                }
                //CHOSEN DAY TOTAL EXPENSE
                else { ?>
                    <style>#year{
                        display: inline;
                    }</style>
                <?php }
                $query = mysqli_query($database, $select);
                $expenseArr = mysqli_fetch_assoc($query);
                $expense = $expenseArr['avg_sum'];
                $finalExpense = money_format('%.2n', $expense);
            } 
            else if ($decision == "Total") {
                //CHOSEN YEAR TOTAL EXPENSE
                if ($list == "Year") {
                    $chosenYear = YEAR_ADJUSTMENT_FACTOR + $subList;
                    $select = "SELECT SUM(total_spent) AS tot_spent FROM current_expense WHERE id='$userID' AND YEAR(curr_date) = '$chosenYear'";
                }
                //CHOSEN MONTH TOTAL EXPENSE (Need to change to account for the year the month belongs to)
                else if ($list == "Month") {
                    $select = "SELECT SUM(total_spent) AS tot_spent FROM current_expense WHERE id='$userID' AND MONTH(curr_date) = '$subList'";
                }
                //CHOSEN DAY TOTAL EXPENSE (Need to change to account for the year and month the day references to)
                else {

                }
                $query = mysqli_query($database, $select);
                $expenseArr = mysqli_fetch_assoc($query);
                $expense = $expenseArr['tot_spent'];
                $finalExpense = money_format('%.2n', $expense);
            }
        } else {
            //make this a pop up message later
            print "One or more of the fields is missing";
        }
    }

    //compares total expense between two days
    if (isset($_POST["compare"])) {
        if (isset($_POST['first_date']) && isset($_POST['second_date'])) {
            $firstDate = $_POST['first_date'];
            $secondDate = $_POST['second_date'];
            $findFirstDate = "SELECT SUM(money_spent) AS total_money_spent FROM categories WHERE id='$userID' AND curr_date='$firstDate'";
            $firstDateArr = mysqli_fetch_assoc(mysqli_query($database, $findFirstDate));
            //for first date
            if (mysqli_num_rows(mysqli_query($database, $findFirstDate)) != 0) {
                $firstDateExpense = $firstDateArr['total_money_spent'];
                //for second date
                $findSecondDate = "SELECT SUM(money_spent) AS tot_money_spent FROM categories WHERE id='$userID' AND curr_date='$secondDate'";
                $secondDateArr = mysqli_fetch_assoc(mysqli_query($database, $findSecondDate));
                if (mysqli_num_rows(mysqli_query($database, $findSecondDate)) != 0) {
                    $secondDateExpense = $secondDateArr['tot_money_spent'];
                } else {
                    //second date does not exist for user
                    $secondDateExpense = "Does not exist";
                }
            } else {
                //First date does not exist for user
                print $firstDateExpense = "Does not exist";
            }
        } else {
            print "Missing a date";
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title>View and Compare Expense</title>
        <link rel="stylesheet" href="Login.css">
    </head>

    <body onload="dropdown()">

        <script type="text/javascript" src="viewExpense.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <form action="viewExpense.php" method="POST">

            <h2>View Expense</h2>
            <select id="decision" name="decision">
                <option name="notSelected" value="default" selected>Choose one</option>
                <option name="avg" value="Average">Average</option>
                <option name="tot" value="Total">Total</option>
            </select>
            <select id="average" name="list" onchange="dropdown()">
                <option name="default" value="default" selected>Choose one</option>
                <option name="day" value="Day">Day</option>
                <option name="month" value="Month">Month</option>
                <option name="year" value="Year">Year</option>
            </select>

            <!-- year and month dropdowns do not work-->
            <div id="year" style="display:none">
                <select>Which year?
                    <option>2019</option>
                </select>
            </div>
            <div id="month" style="display:none">
                <select value="Which month?">
                    <option>July</option>
                </select>
            </div>
            
            <?php 
                if (isset($_POST["list"]) && $list == "Day") { ?>
                    <script language="javascript">
                    document.getElementById("month").style.display = "block";
                    </script>
                <?php }
            ?>

            <select id="subSelect" name="subList" value="---">
                <option value="---">Choose one</option>
            </select>

            <input type="submit" name="output" value="Get">
            <br/>
            $<input type="text" value="<?php print $finalExpense ?>" placeholder="0.00" readonly>
            <br/>

            <h3>Compare Total Expense Between Two Days</h3>
            <input type="date" name="first_date">
            <input type="date" name="second_date">
            <input type="submit" name="compare" value="Compare">
            <br/>
            $<input type="text" value="<?php print $firstDateExpense ?>" readonly>
            $<input type="text" value="<?php print $secondDateExpense ?>" readonly>

            <br/>
            <a href="home.php">Home</a>
            <br/>
            <a href="logout.php">Log out</a>

        </form>

    </body>
</html>