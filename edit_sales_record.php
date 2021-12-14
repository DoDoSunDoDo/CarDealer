<?php 
include('lib/common.php');

// constraint: page will be directed to the following if no username assigned
if (!isset($_SESSION['username'])) {
	header('Location: display_main_page.php');
	exit();
}
?>

<?php include("lib/header.php");?>
<head>
<title>Sales Record Page</title>
</head>


<html>
<body>
    <div id="main_container">
        <div class="center_content">
            <div class="text_box">
                <form action="edit_sales_record.php" method="post" enctype="multipart/form-data">
                    <div class="title">Jaunty Jalopies Sell Vehicle</div>
					<div class="sales_order_form_row">
                        <label class="sell_vehicle_label">VIN:</label>
                        <?php echo $_SESSION["vin"];?>
                    </div> 
					<div class="sales_order_form_row">
					    <label class="sales_order_form_row">Customer:</label>
                        <?php echo $_SESSION["customerID"];?>
					</div> 
					<div class="sales_order_form_row">
					    <label class="sales_order_form_row">Username:</label>
                        <?php echo $_SESSION["username"];?>
					</div> 
                    <div class="sales_order_form_row">
                        <label class="sell_vehicle_label">Purchase Date:</label>
                        <input type="date" name="purchase_date" value="mm/dd/yyyy" class="sell_vehicle_input"/> 
                    </div>   
                    <div class="sales_order_form_row">
                        <label class="sell_vehicle_label">Sold Price:</label>
                        <input type="number" name="sold_price" value="" class="sell_vehicle_input"/>
                    </div>  
					<div>
					<a  href="edit_sales_record.php" style="float: right;"><button type='submit' name="add">ADD SALE</button></a>
					</div>    
                </form>
				<?php
				if ($_SERVER['REQUEST_METHOD']=='POST'){
					$purchase_date = strval(mysqli_real_escape_string($db, $_POST['purchase_date']));
					$sold_price = strval(mysqli_real_escape_string($db, $_POST['sold_price']));
				    $invoice_price_query = "SELECT 0.95 * InvoicePrice as MinSoldPrice 
					                        FROM Vehicle 
											WHERE VIN = '{$_SESSION['vin']}'";
					$invoice_price_result = mysqli_query($db, $invoice_price_query);
					while ($row = mysqli_fetch_array($invoice_price_result, MYSQLI_NUM)) {
						if ($sold_price <= $row[0]) {
							print "Please update sold price!";
						}
						else {
							$insert_sales_record_query = "INSERT INTO SalesRecord (PurchaseDate, SoldPrice, VIN, CustomerID, UserName)
														  VALUES ('$purchase_date', '$sold_price', '{$_SESSION['vin']}', '{$_SESSION['customerID']}', '{$_SESSION['username']}')";
						    if (mysqli_query($db, $insert_sales_record_query)) {
								print "New Sales Record added successfully!";
							}
							else {
								print "New Sales Record added failed...";
							}
						}
					}

				}
				?>
            </div>
        </div>
		
        <?php include("lib/error.php"); ?>
        <div class="clear"></div>
    </div>
</body>
</html>