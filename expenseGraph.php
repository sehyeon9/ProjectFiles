<?php
    include 'dbConnection.php'; 
    include 'LoginBackend.php';
    session_start();

    $userID = $_SESSION['id'];

    //gets the total money spent on each category
    $mortgageQuery = "SELECT SUM(money_spent) AS mortgage FROM categories WHERE id='$userID' AND curr_date=CURDATE() AND category_name='Mortgage'";
    $mortgageAmount = mysqli_query($database, $mortgageQuery);
    $mortgageExpense = mysqli_fetch_assoc($mortgageAmount);
    $mortgageTotal = $mortgageExpense['mortgage'];

    $utilityQuery = "SELECT SUM(money_spent) AS utilities FROM categories WHERE id='$userID' AND curr_date=CURDATE() AND category_name='Public Utility'";
    $utilityAmount = mysqli_query($database, $utilityQuery);
    $utilityExpense = mysqli_fetch_assoc($utilityAmount);
    $utilityTotal = $mortgageExpense['utilities'];

    $carQuery = "SELECT SUM(money_spent) AS car FROM categories WHERE id='$userID' AND curr_date=CURDATE() AND category_name='Car'";
    $carAmount = mysqli_query($database, $carQuery);
    $carExpense = mysqli_fetch_assoc($carAmount);
    $carTotal = $carExpense['car'];

    $insuranceQuery = "SELECT SUM(money_spent) AS insurance FROM categories WHERE id='$userID' AND curr_date=CURDATE() AND category_name='Insurance'";
    $insuranceAmount = mysqli_query($database, $insuranceQuery);
    $insuranceExpense = mysqli_fetch_assoc($insuranceAmount);
    $insuranceTotal = $insuranceExpense['insurance'];

    $giftQuery = "SELECT SUM(money_spent) AS gift FROM categories WHERE id='$userID' AND curr_date=CURDATE() AND category_name='Gifts'";
    $giftAmount = mysqli_query($database, $giftQuery);
    $giftExpense = mysqli_fetch_assoc($giftAmount);
    $giftTotal = $giftExpense['gift'];

    $clothesQuery = "SELECT SUM(money_spent) AS clothes FROM categories WHERE id='$userID' AND curr_date=CURDATE() AND category_name='Clothes'";
    $clothesAmount = mysqli_query($database, $clothesQuery);
    $clothesExpense = mysqli_fetch_assoc($clothesAmount);
    $clothesTotal = $clothesExpense['clothes'];

    $healthQuery = "SELECT SUM(money_spent) AS health FROM categories WHERE id='$userID' AND curr_date=CURDATE() AND category_name='Health Expenses'";
    $healthAmount = mysqli_query($database, $healthQuery);
    $healthExpense = mysqli_fetch_assoc($healthAmount);
    $healthTotal = $healthExpense['health'];

    $otherQuery = "SELECT SUM(money_spent) AS other FROM categories WHERE id='$userID' AND curr_date=CURDATE() AND category_name='Other'";
    $otherAmount = mysqli_query($database, $otherQuery);
    $otherExpense = mysqli_fetch_assoc($otherAmount);
    $otherTotal = $otherExpense['other'];

    //make each category and the expense spent on it the data values of column graph
    $dataPoints = array(
        array("y" => $mortgageTotal, "label" => "Mortgage"),
        array("y" => $utilityTotal, "label" => "Public Utility"),
        array("y" => $carTotal, "label" => "Car"),
        array("y" => $insuranceTotal, "label" => "Insurance"),
        array("y" => $giftTotal, "label" => "Gifts"),
        array("y" => $clothesTotal, "label" => "Clothes"),
        array("y" => $healthTotal, "label" => "Health Expenses"),
        array("y" => $otherTotal, "label" => "Other")
    );

    //the total spent today
    $displayTotal = $mortgageTotal + $utilityTotal + $carTotal + $insuranceTotal + $giftTotal + $clothesTotal + $healthTotal + $otherTotal;

?>

<!DOCTYPE HTML>
<html>
    <head>
        <title> Expense Graph </title>
        <!-- uses canvasJS to create the column graph with user's categorized expenses -->
        <script>
            window.onload = function() {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
	            theme: "light2",
	            title:{
		            text: "Today's Expenses"
	            },
	            axisY: {
		            title: "USD"
	            },
	            data: [{
		            type: "column",
		            yValueFormatString: "#,##0.## USD",
		            dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	            }]
            });
            chart.render();
            }
        </script>
    </head> 

    <body>
            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

            <h2> Total Expense </h2>
            $<input type="text" name="total_spent" value="<?php print money_format('%.2n', $displayTotal) ?>" readonly>
            <br/>
            <a href="home.php">Home</a>
    </body>

</html>