<?php
include('lib/common.php');

// constraint: page must be directed to after log in
if (!isset($_SESSION['username'])) {
    header('Location: display_main_page.php');
    exit();
}
?>

<?php include("lib/header.php"); ?>
<title>View Report Page</title>

<head>

<body>
    <div id="main_container">
        <div class="center_content">
            <div class="center_left">
                <div class="title_name">View Repairs by Manufacturer</div>

                <div class="features">   	
						<div class="profile_section">
							<table id = "table">
								<tr >
									<th >Manufacturer Name</th>
									<th >Repair Number</th>
									<th >Parts Cost</th>
                                    <th >Labor Cost</th>
                                    <th >Total Cost</th>
								</tr>
                               
                                <?php								
                                    $query = "SELECT t.VehicleType AS vehicle_type, m.ManufacturerName AS manufacturer_name, COUNT(r.RepairID) AS repair_number, ROUND(SUM(coalesce(parts_cost,0))) as total_parts_cost, ROUND(COALESCE(SUM(LaborCharge),0)) AS labor_Cost, ROUND(SUM(coalesce(LaborCharge,0)) + SUM(coalesce(parts_cost,0))) AS total_Cost FROM Manufacturer AS m JOIN Vehicle AS v ON m.ManufacturerID = v.ManufacturerID Join (SELECT vt.VIN, CASE WHEN VIN IN (SELECT VIN FROM car) THEN 'Car' WHEN VIN IN (SELECT VIN FROM truck) THEN 'Truck' WHEN VIN IN (SELECT VIN FROM van) THEN 'Van' WHEN VIN IN (SELECT VIN FROM suv) THEN 'Suv' WHEN VIN IN (SELECT VIN FROM convertible) THEN 'Convertible' END AS VehicleType FROM vehicle AS vt) AS t ON v.VIN = t.VIN LEFT JOIN RepairRecord AS r ON v.VIN = r.VIN LEFT JOIN (SELECT RepairID, SUM(PartQuantity*PartPrice) AS parts_cost FROM Parts GROUP BY 1) AS p ON r.RepairID = p.RepairID GROUP BY 2 having COUNT(r.RepairID) >0 ORDER BY 2 ASC;";
                                    
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: view repairs by manufacturer <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        $_SESSION['vehicle_type'] = '';
                                        $vehicle_type= urlencode($row['vehicle_type']);
                                        print "<tr>";
                                        print "<td><a href='vehicle_type_repair_detail.php?vehicle_type=$vehicle_type'>{$row['manufacturer_name']} </td>";
                                        print "<td>{$row['repair_number']}</td>";
                                        print "<td>{$row['total_parts_cost']}</td>";
                                        print "<td>{$row['labor_Cost']}</td>";
                                        print "<td>{$row['total_Cost']}</td>";
                                        print "</tr>";							
                                    }	
                                    								
                                ?>
							</table>
                            <pre></pre>
						    <a  href="update_main_page.php" style="float: right;"><button>MAIN PAGE</button></a>
                  
                        </div>
					 </div> 
                 </div> 
                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 
			</div>    

               <?php include("lib/footer.php"); ?>
		 
		</div>
	</body>
</html>