<html>
<body>
<?php 
include('lib/common.php');
include("lib/header.php");
if (!isset($_SESSION['username'])) {
	header('Location: display_main_page.php');
	exit();
}
$repair_id = $_SESSION['repair_id'];
if ($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['back'])) {
        header('Location: view_vehicle_repair.php?vin='.$_SESSION['vin'].'&search=');
    }
    $part_num = mysqli_real_escape_string($db, $_POST['part_number']);
    $part_price = mysqli_real_escape_string($db, $_POST['part_price']);
    $part_quantity = mysqli_real_escape_string($db, $_POST['part_quantity']);
    $part_vendor = mysqli_real_escape_string($db, $_POST['part_vendor']);
    //check part number
    $query0 = "SELECT PartQuantity FROM parts 
    WHERE RepairID = '$repair_id' 
    AND PartNumber = '$part_num'
    AND PartPrice = '$part_price'
    AND PartVendor = '$part_vendor'";
    $result0 = mysqli_query($db,$query0);
    if (mysqli_num_rows($result0)==1){
        
        $prev_quantity = mysqli_fetch_array($result0, MYSQLI_NUM);
        $part_quantity = $part_quantity + $prev_quantity[0];
        $query1 = "UPDATE parts SET PartQuantity = '$part_quantity'
        WHERE RepairID = '$repair_id' 
        AND PartNumber = '$part_num'
        AND PartPrice = '$part_price'
        AND PartVendor = '$part_vendor'";
        mysqli_query($db,$query1);
    }
    else{
        $query2 = "INSERT INTO parts(PartNumber, RepairID, PartPrice, PartQuantity, PartVendor) 
        VALUES ('$part_num','$repair_id','$part_price','$part_quantity','$part_vendor')";
        mysqli_query($db,$query2);
    }
    //same part number different price is impossible in this case...
}
?>
<div id="main_container">
	<div class = "center_content">
        <div class ="text_box">
        <form action="add_parts.php" method="post" enctype="multipart/form-data">
		    <div class = "title"> Add parts</div>
            <div class="login_form_row">
                <label class="login_label">Part Number</label>
				<input type="text" name="part_number" value="" class="login_input"/>
            </div>     
            <div class="login_form_row">
                <label class="login_label">Part Price</label> 
				<input type="text" name="part_price" value="" class="login_input"/>
            </div>
            <div class="login_form_row">
                <label class="login_label">Part Quantity</label>
				<input type="text" name="part_quantity" value="" class="login_input"/>
            </div>
            <div class="login_form_row">
                <label class="login_label">Part Vendor</label>
				<input type="text" name="part_vendor" value="" class="login_input"/>
            </div>
            <div class="login_form_row">
                <a  href='add_parts.php' style='float: right;'><button>Add Parts</button></a>
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

INSERT INTO parts(PartNumber, RepairID, PartPrice, PartQuantity, PartVendor) VALUES (10,6,10,1,"v10");