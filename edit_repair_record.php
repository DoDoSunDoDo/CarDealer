<html>
<body>
<?php 
include('lib/common.php');
include("lib/header.php");
if (!isset($_SESSION['username'])) {
	header('Location: display_main_page.php');
	exit();
}
if (($_SESSION['editable']==FALSE)||($_SESSION['eligible']==0)){
    header('Location: view_vehicle_repair.php?vin='.$_SESSION['vin'].'&search=');
}
if ($_SERVER['REQUEST_METHOD']=="POST"){
    if(isset($_POST['back'])) {
        header('Location: view_vehicle_repair.php?vin='.$_SESSION['vin'].'&search=');
    }
    $repair_id = $_SESSION['repair_id'];
    //complete date
    if(isset($_POST['complete'])) {
        $query0 = "UPDATE repairrecord SET CompleteDate = CURRENT_DATE WHERE RepairID = '$repair_id'";
        mysqli_query($db,$query0);
        header('Location: view_vehicle_repair.php?vin='.$_SESSION['vin'].'&search=');
        exit();
    }
    //labor charge
    $labor_charge = mysqli_real_escape_string($db, $_POST['labor_charge']);
    $query1 = "SELECT LaborCharge FROM RepairRecord WHERE RepairID = '$repair_id'";
    $result1 = mysqli_query($db,$query1);
    $prev_labor_charge = mysqli_fetch_array($result1, MYSQLI_NUM);
    $login_username = $_SESSION['username'];
    $query2 = "SELECT * FROM Owner WHERE UserName = '$login_username'";
    $result2 = mysqli_query($db,$query2);
    
    if ((mysqli_num_rows($result2) == 0)&&($labor_charge<$prev_labor_charge[0])){
        array_push($error_msg,"Labor charge is less than the previous labor charge on record. Please enter a valid labor charge.");
    }
    else {
        $query3 = "UPDATE repairrecord SET LaborCharge = '$labor_charge' WHERE RepairID = '$repair_id'";
        mysqli_query($db,$query3);
    }
}
?>

<div id="main_container">
	<div class = "center_content">
        <div class ="text_box">
        <form action="edit_repair_record.php" method="POST" enctype="multipart/form-data">
		    <div class = "title"> Update Repair Record</div>
            <div class="login_form_row">
                <label class="login_label">Complete Repair</label>
				<input type="submit" name="complete" value="Complete"/>
            </div>     
            <div class="login_form_row">
                <label class="login_label">Labor Charge</label> 
				<input type="text" name="labor_charge" value="" class="login_input"/>
            </div>
            <div class="login_form_row">
                <label class="login_label">Update the record</label>
                <a  href='edit_repair_record.php' style='float: right;'><button>Update</button></a>
			</div>
            <div class="login_form_row">
                <label class="login_label">Back to record</label>
                <input type="submit" name="back" value="BACK"/>
		    </div>
        </form>
        <div class="login_form_row">
                <label class="login_label">Add parts</label>
                <a  href='add_parts.php' style='float: right;'><button>Add Parts</button></a>
			</div>
        </div>
    </div>
    <?php include("lib/error.php"); ?>
    <div class="clear"></div>
</div>
</body>
</html>
