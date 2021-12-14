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
                <div class="title_name">View Average Time in Inventory</div>

                <div class="features">   	
						<div class="profile_section">
							<table id = "table">
								<tr >
									<th >Vehicle Type</th>
									<th >Average Time in Inventory</th>
								</tr>
                               
                                <?php								
                                    $query = "SELECT VehicleType, CASE WHEN add_to_sale_days is null THEN 'N/A' ELSE add_to_sale_days END AS add_to_sale_days FROM ( SELECT tt.VehicleType, ROUND(AVG(datediff(sr.PurchaseDate, v.AddDate))) AS add_to_sale_days FROM Vehicle v LEFT JOIN SalesRecord sr ON sr.VIN = v.VIN Join (SELECT vt.VIN, CASE WHEN VIN IN (SELECT VIN FROM car) THEN 'Car' WHEN VIN IN (SELECT VIN FROM truck) THEN 'Truck' WHEN VIN IN (SELECT VIN FROM van) THEN 'Van' WHEN VIN IN (SELECT VIN FROM suv) THEN 'Suv' WHEN VIN IN (SELECT VIN FROM convertible) THEN 'Convertible' END AS VehicleType FROM vehicle AS vt) AS tt ON v.VIN = tt.VIN GROUP BY 1 ) AS vi;";
                                    ;
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: view Average Time in Inventory <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['VehicleType']} </td>";
                                        print "<td>{$row['add_to_sale_days']}</td>";
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