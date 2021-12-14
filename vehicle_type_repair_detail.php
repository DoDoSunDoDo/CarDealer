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
<?php
$_SESSION['vehicle_type'] = ''; // reset to default setting

    if(!isset($_SESSION['vehicle_type']) or empty($_SESSION['vehicle_type'])) {
        // below setting is a temporary workaround
        $_SESSION['vehicle_type'] = explode("?", $_GET['vehicle_type'], 2)[0];
        // $_SESSION['customer_id'] = explode("=", $_GET['customer_name'], 3)[1];
        if ($debug) {
            echo '<br> Test #: 1 </br>';
            echo '<br> Current vehicle_type being tracked is: '. $_SESSION['vehicle_type']. '</br>';
            // echo '<br> Current customer_id being tracked is: '. $_SESSION['customer_id']. '</br>';
        }
    }
                    
?>
<body>
    <div id="main_container">
        <div class="center_content">
            <div class="center_left">
                <div class="title_name">Repair Detail for <?php print $_SESSION['vehicle_type']; ?> Type </div>
                <div class="features">   	
						<div class="profile_section">
                        <form method="post">
							<table id = "table">
								<tr >
									<th >Vehicle Type</th>
									<th >Repair Count</th>
                                    <th >Total Parts Cost</th>
                                    <th >Labor Cost</th>
                                    <th >Total Cost</th>
								</tr>
                                <?php
                                    $vehicle_type = urlencode($_SESSION['vehicle_type']);		
                                    $query = "select the_table.VehicleType AS vehicle_type, the_table.repair_count AS repair_count, the_table.total_parts_cost AS total_parts_cost , the_table.labor_cost AS labor_cost, the_table.total_cost AS total_cost FROM (SELECT VehicleType, modelName, repair_count, total_parts_cost, labor_cost, total_cost FROM ( SELECT tt.VehicleType, v.ModelName, COUNT(rp.RepairID) AS repair_count, ROUND(SUM(rp.parts_cost)) AS total_parts_cost, ROUND(SUM(rp.LaborCharge)) AS labor_cost, ROUND(SUM(rp.parts_cost)+SUM(rp.LaborCharge)) AS total_cost FROM (SELECT r.RepairID, r.VIN, r.LaborCharge, COALESCE(p.parts_cost,0) AS parts_cost FROM RepairRecord AS r LEFT JOIN (SELECT RepairID, SUM(PartQuantity*PartPrice) AS parts_cost FROM Parts GROUP BY 1) AS p ON r.RepairID = p.RepairID) as rp INNER JOIN Vehicle AS v ON v.VIN = rp.VIN Join (SELECT vt.VIN, CASE WHEN VIN IN (SELECT VIN FROM car) THEN 'Car' WHEN VIN IN (SELECT VIN FROM truck) THEN 'Truck' WHEN VIN IN (SELECT VIN FROM van) THEN 'Van' WHEN VIN IN (SELECT VIN FROM suv) THEN 'Suv' WHEN VIN IN (SELECT VIN FROM convertible) THEN 'Convertible' END AS VehicleType FROM vehicle AS vt) AS tt ON v.VIN = tt.VIN INNER JOIN Manufacturer m ON m.ManufacturerID = v.ManufacturerID GROUP BY VehicleType ) t WHERE repair_count is not null and VehicleType is not null ORDER BY VehicleType, ISNULL(ModelName) desc, repair_count desc ) the_table where the_table.modelName is not null AND the_table.VehicleType = '$vehicle_type'";
                                    
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: view gorss income <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['vehicle_type']}</a></td>";
                                        print "<td>{$row['repair_count']}</td>";
                                        print "<td>{$row['total_parts_cost']}</td>";
                                        print "<td>{$row['labor_cost']}</td>";
                                        print "<td>{$row['total_cost']}</td>";                                    
                                        print "</tr>";							
                                    }	
                                ?>
							</table>
                            <div class="title_name"> </div>
                 <div class="title_name"><br>Repair Detail for <?php print $_SESSION['vehicle_type']; ?> Models </div>
						<div class="profile_section">
							<table id = "table">
								<tr >
									<th >Model Name</th>
									<th >Repair Count</th>
									<th >Total Part Cost</th>
                                    <th >Labor Cost</th>
                                    <th >Total Cost</th>
								</tr>
                                <?php
                                    $vehicle_type = urlencode($_SESSION['vehicle_type']);		
                                    $query = "SELECT VehicleType AS vehicle_type, modelName AS model_name, repair_count AS repair_count, ROUND(total_parts_cost) AS total_parts_cost, labor_cost AS labor_cost, total_cost AS total_cost FROM ( SELECT tt.VehicleType, v.ModelName, COUNT(rp.RepairID) AS repair_count, SUM(rp.parts_cost) AS total_parts_cost, ROUND(SUM(rp.LaborCharge) )AS labor_cost, ROUND(SUM(rp.parts_cost)+SUM(rp.LaborCharge)) AS total_cost FROM (SELECT r.RepairID, r.VIN, r.LaborCharge, COALESCE(p.parts_cost,0) AS parts_cost FROM RepairRecord AS r LEFT JOIN (SELECT RepairID, SUM(PartQuantity*PartPrice) AS parts_cost FROM Parts GROUP BY 1) AS p ON r.RepairID = p.RepairID) as rp INNER JOIN Vehicle AS v ON v.VIN = rp.VIN Join (SELECT vt.VIN, CASE WHEN VIN IN (SELECT VIN FROM car) THEN 'Car' WHEN VIN IN (SELECT VIN FROM truck) THEN 'Truck' WHEN VIN IN (SELECT VIN FROM van) THEN 'Van' WHEN VIN IN (SELECT VIN FROM suv) THEN 'Suv' WHEN VIN IN (SELECT VIN FROM convertible) THEN 'Convertible' END AS VehicleType FROM vehicle AS vt) AS tt ON v.VIN = tt.VIN INNER JOIN Manufacturer m ON m.ManufacturerID = v.ManufacturerID GROUP BY ModelName ) t WHERE repair_count is not null and VehicleType is not null and t.VehicleType = '$vehicle_type' ORDER BY repair_count desc";
                                    
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: view gorss income <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['model_name']}</a></td>";
                                        print "<td>{$row['repair_count']}</td>";
                                        print "<td>{$row['total_parts_cost']}</td>";
                                        print "<td>{$row['labor_cost']}</td>";
                                        print "<td>{$row['total_cost']}</td>";           
                                        print "</tr>";							
                                    }	
                                    								
                                ?>
							</table>
                        </div>
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