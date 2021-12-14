<?php 
include('lib/common.php');
// constraint: page must be directed to after log in
if (!isset($_SESSION['username'])) {
	header('Location: display_main_page.php');
	exit();
}
?>

<?php include("lib/header.php"); ?>
<head>
<title>Add Vehicle Page</title>
</head>

<html>
<body>
	<div id="main_container">
	<div class="center_content" height: 800px;>
			<div class="text_box">
				<div class="title">New Vehicle Form</div>          
						<form name="submit" action="view_add_vehicle.php" method="post">
							<table>

								<tr>
									<td class="item_label">VIN</td>
									<td>
										<input type="text" name="VIN" value="" />					
									</td>
								</tr>

								<tr>
									<td class="item_label">Add Date</td>
									<td>
										<input type="date" name="AddDate" value="" />					
									</td>
								</tr>
								
								<tr>
									<td class="item_label">Model Year</td>
									<td>
										<input type="int" name="ModelYear" value="" />					
									</td>
								</tr>

								<tr>
									<td class="item_label">Model Name</td>
									<td>
										<input type="text" name="ModelName" value="" />					
									</td>
								</tr>

								<tr>
									<!-- TODO: color is better to use dropdown and add button to prevent typo -->
									<td class="item_label">Colors</td>
									<td>
										<input type="text" name="colors" value="Please separate multiple colors by comma and space, e.g. red, navy, black" />					
									</td>
								</tr>
								
								<tr>
									<td class="item_label">Invoice Price</td>
									<td>
										<input type="float" name="InvoicePrice" value="" />					
									</td>
								</tr>

								<tr>
									<td class="item_label">Additional Info</td>
									<td>
										<input type="text" name="AdditionalInfo" value="" />					
									</td>
								</tr>

								<tr>
									<td class="item_label">Vehicle Type</td>
									<td>
										<select name="VehicleType">
											<option value="Car" <?php if ($row['VehicleType'] == 'Car') { print 'selected="true"';} ?>>Car</option>
											<option value="SUV" <?php if ($row['VehicleType'] == 'SUV') { print 'selected="true"';} ?>>SUV</option>
											<option value="Convertible" <?php if ($row['VehicleType'] == 'Convertible') { print 'selected="true"';} ?>>Convertible</option>
											<option value="Van" <?php if ($row['VehicleType'] == 'Van') { print 'selected="true"';} ?>>Van</option>
											<option value="Truck" <?php if ($row['VehicleType'] == 'Truck') { print 'selected="true"';} ?>>Truck</option>
										</select>
									</td>

									<tr>
									<td class="item_label">Manufacturer</td>
									<td>
										<select name="ManufacturerName">
											<option value="Acura" <?php if ($row['ManufacturerName'] == 'Acura') { print 'selected="true"';} ?>>Acura</option>
											<option value="Alfa Romeo" <?php if ($row['ManufacturerName'] == 'Alfa Romeo') { print 'selected="true"';} ?>>Alfa Romeo</option>
											<option value="Aston Martin" <?php if ($row['ManufacturerName'] == 'Aston Martin') { print 'selected="true"';} ?>>Aston Martin</option>
											<option value="Audi" <?php if ($row['ManufacturerName'] == 'Audi') { print 'selected="true"';} ?>>Audi</option>
											<option value="Bently" <?php if ($row['ManufacturerName'] == 'Bently') { print 'selected="true"';} ?>>Bently</option>
											<option value="BMW" <?php if ($row['ManufacturerName'] == 'BMW') { print 'selected="true"';} ?>>BMW</option>
											<option value="Buick" <?php if ($row['ManufacturerName'] == 'Buick') { print 'selected="true"';} ?>>Buick</option>
											<option value="Cadillac" <?php if ($row['ManufacturerName'] == 'Cadillac') { print 'selected="true"';} ?>>Cadillac</option>
											<option value="Chevrolet" <?php if ($row['ManufacturerName'] == 'Chevrolet') { print 'selected="true"';} ?>>Chevrolet</option>
											<option value="Chrysler" <?php if ($row['ManufacturerName'] == 'Chrysler') { print 'selected="true"';} ?>>Chrysler</option>
											<option value="Dodge" <?php if ($row['ManufacturerName'] == 'Dodge') { print 'selected="true"';} ?>>Dodge</option>
											<option value="Ferrari" <?php if ($row['ManufacturerName'] == 'Ferrari') { print 'selected="true"';} ?>>Ferrari</option>
											<option value="FIAT" <?php if ($row['ManufacturerName'] == 'FIAT') { print 'selected="true"';} ?>>FIAT</option>
											<option value="Ford" <?php if ($row['ManufacturerName'] == 'Ford') { print 'selected="true"';} ?>>Ford</option>
											<option value="Freightliner" <?php if ($row['ManufacturerName'] == 'Freightliner') { print 'selected="true"';} ?>>Freightliner</option>
											<option value="Genesis" <?php if ($row['ManufacturerName'] == 'Genesis') { print 'selected="true"';} ?>>Genesis</option>
											<option value="GMC" <?php if ($row['ManufacturerName'] == 'GMC') { print 'selected="true"';} ?>>GMC</option>
											<option value="Honda" <?php if ($row['ManufacturerName'] == 'Honda') { print 'selected="true"';} ?>>Honda</option>
											<option value="Hyundai" <?php if ($row['ManufacturerName'] == 'Hyundai') { print 'selected="true"';} ?>>Hyundai</option>
											<option value="INFINITI" <?php if ($row['ManufacturerName'] == 'INFINITI') { print 'selected="true"';} ?>>INFINITI</option>
											<option value="Jaguar" <?php if ($row['ManufacturerName'] == 'Jaguar') { print 'selected="true"';} ?>>Jaguar</option>
											<option value="Jeep" <?php if ($row['ManufacturerName'] == 'Jeep') { print 'selected="true"';} ?>>Jeep</option>
											<option value="Kia" <?php if ($row['ManufacturerName'] == 'Kia') { print 'selected="true"';} ?>>Kia</option>
											<option value="Lamborghini" <?php if ($row['ManufacturerName'] == 'Jeep') { print 'selected="true"';} ?>>Lamborghini</option>
											<option value="Land Rover" <?php if ($row['ManufacturerName'] == 'Jeep') { print 'selected="true"';} ?>>Land Rover</option>
										</select>
									</td>
							</table>
							<a  href="view_add_vehicle.php" style="float: right;"><button type='submit' name="save">SAVE</button></a>
						</form>
							<a  href="update_main_page.php" style="float: right;"><button>MAIN PAGE</button></a>
			
							<?php include("lib/error.php"); ?>
				
			<div class="clear"></div> 		
	</div>    

	<?php include("lib/footer.php"); ?>
				
	</div>
</body>
</html>
