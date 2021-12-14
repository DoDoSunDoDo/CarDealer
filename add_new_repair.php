<html>
<body>
<?php 
include('lib/common.php');
include("lib/header.php");
if (!isset($_SESSION['username'])) {
	header('Location: display_main_page.php');
	exit();
}
$vin = $_SESSION['vin']; 
$service_writer = $_SESSION['username'];
$customer_id = $_SESSION['customerID'];
if ($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['back'])) {
        header('Location: view_vehicle_repair.php?vin='.$vin.'&search=');
    }
    if(isset($_POST['complete'])) {
        $odometer_reading = mysqli_real_escape_string($db, $_POST['odometer_reading']);
        $labor_charge = mysqli_real_escape_string($db, $_POST['labor_charge']);
        $description = mysqli_real_escape_string($db, $_POST['description']);

        $query0 = "INSERT INTO repairrecord(VIN, CustomerID, UserName, StartDate, LaborCharge, RepairDescription, OdometerReading) 
        VALUES ('$vin','$customer_id','$service_writer',CURRENT_DATE,'$labor_charge','$description','$odometer_reading')";
        mysqli_query($db,$query0);
    }
}
?>
<div id="main_container">
	<div class = "center_content">
        <div class ="text_box">
            <div class = "title">Add Repair Record: 2 More Information</div>
            <form action="add_new_repair.php" method="post" enctype="multipart/form-data">
                <div class="login_form_row">
                    <label class="login_label">Odometer Reading</label>
                    <input type="text" name="odometer_reading" value="" class="login_input"/>
                </div>
                <div class="login_form_row">
                    <label class="login_label">Labor Charge</label>
                    <input type="text" name="labor_charge" value="" class="login_input"/>
                </div>          
                <div class="login_form_row">
                    <label class="login_label">Description</label>
                    <input type="text" name="description" value="" class="login_input"/>
                </div>
                <div class="login_form_row">
                    <input type="submit" name="complete" value="CREATE"/>
                </div>
                <div class="login_form_row">
                    <label class="login_label">Back to record</label>
                    <input type="submit" name="back" value="BACK"/>
                </div>
            </form>
        </div>
    </div>
    <?php include("lib/error.php"); ?>
    <div class="clear"></div>
</div>
</body>
</html>
