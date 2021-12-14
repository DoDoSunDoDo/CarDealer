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
$_SESSION['customer_name'] = ''; // reset to default setting

    if(!isset($_SESSION['customer_name']) or empty($_SESSION['customer_name'])) {
        // below setting is a temporary workaround
        $_SESSION['customer_name'] = explode("?", $_GET['customer_name'], 2)[0];
        $_SESSION['customer_id'] = explode("=", $_GET['customer_name'], 3)[1];
        if ($debug) {
            echo '<br> Test #: 1 </br>';
            echo '<br> Current customer_name being tracked is: '. $_SESSION['customer_name']. '</br>';
            echo '<br> Current customer_id being tracked is: '. $_SESSION['customer_id']. '</br>';
        }
    }
                    
?>
<body>
    <div id="main_container">
 -->
        <div class="center_content">
            <div class="center_left">
                <div class="title_name">Sales Detail for <?php print $_SESSION['customer_name']; ?> </div>
                <div class="features">   	
						<div class="profile_section">
                        <form method="post">
							<table id = "table">
								<tr >
									<th >Purchase Date</th>
									<th >Sold Price</th>
									<th >VIN</th>
                                    <th >Model Year</th>
                                    <th >Manufacturer Name</th>
                                    <th >Model Name</th>
                                    <th >Salesperson</th>
								</tr>
                                <?php
                                    $customer_id = urlencode($_SESSION['customer_id']);		
                                    $query = "SELECT s.PurchaseDate AS purchase_date, s.SoldPrice AS sold_price, s.VIN AS vin, v.ModelYear AS model_year, m.ManufacturerName AS manufacturer_name, v.ModelName AS model_name, CONCAT(u.FirstName ,' ', u.LastName) AS salesperson_name FROM SalesRecord s INNER JOIN Vehicle v ON s.VIN=v.VIN INNER JOIN Manufacturer m ON v.ManufacturerID = m.ManufacturerID INNER JOIN SalesPerson sp ON s.Username=sp.UserName INNER JOIN User u ON u.UserName = sp.UserName WHERE s.CustomerID=$customer_id ORDER BY s.PurchaseDate DESC, s.VIN;";                      
                                    
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: view gorss income <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['purchase_date']}</a></td>";
                                        print "<td>{$row['sold_price']}</td>";
                                        print "<td>{$row['vin']}</td>";
                                        print "<td>{$row['model_year']}</td>";
                                        print "<td>{$row['manufacturer_name']}</td>";
                                        print "<td>{$row['model_name']}</td>";    
                                        print "<td>{$row['salesperson_name']}</td>";                                   
                                        print "</tr>";							
                                    }	
                                ?>
							</table>
            <div class="title_name"> </div>
                 <div class="title_name"><br>Repair Detail for <?php print $_SESSION['customer_name']; ?> </div>
						<div class="profile_section">
							<table id = "table">
								<tr >
									<th >Service Writer</th>
									<th >Start Date</th>
									<th >Complete Date</th>
                                    <th >VIN</th>
                                    <th >Odometer</th>
                                    <th >Parts Cost</th>
                                    <th >Labor Cost</th>
                                    <th >Total Cost</th>
								</tr>
                                <?php
                                    $customer_id = urlencode($_SESSION['customer_id']);		
                                    $query = "SELECT Concat(u.FirstName ,' ', u.LastName) AS service_writer_name, rp.StartDate AS start_date1, rp.CompleteDate AS complete_date, rp.VIN AS vin, rp.OdometerReading AS odo, ROUND(SUM(parts_cost)) AS parts_cost, ROUND(SUM(rp.LaborCharge)) AS labor_cost, ROUND(SUM(rp.LaborCharge)+SUM(rp.parts_cost)) AS total_repair_cost FROM ( SELECT r.VIN, r.UserName, r.CustomerID, r.StartDate, r.CompleteDate, r.LaborCharge, r.OdometerReading, coalesce(p.parts_cost,0) as parts_cost FROM RepairRecord r LEFT JOIN (SELECT RepairID, SUM(PartPrice*PartQuantity) AS parts_cost FROM Parts GROUP BY 1) p ON r.RepairID = p.RepairID ) AS rp INNER JOIN ServiceWriter sw ON rp.UserName=sw.UserName INNER JOIN User u ON sw.UserName=u.UserName WHERE rp.CustomerID = $customer_id GROUP BY 1,2,3,4,5 ORDER BY StartDate DESC, CASE WHEN CompleteDate is null THEN 1 ELSE 2 END, CompleteDate DESC, VIN;";
                                    
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: view gorss income <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['service_writer_name']}</a></td>";
                                        print "<td>{$row['start_date1']}</td>";
                                        print "<td>{$row['complete_date']}</td>";
                                        print "<td>{$row['vin']}</td>";
                                        print "<td>{$row['odo']}</td>";
                                        print "<td>{$row['parts_cost']}</td>";    
                                        print "<td>{$row['labor_cost']}</td>";
                                        print "<td>{$row['total_repair_cost']}</td>";                                       
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