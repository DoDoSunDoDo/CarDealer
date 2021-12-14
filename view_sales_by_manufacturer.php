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
                <div class="title_name">View Sales by Manufacturer</div>

                <div class="features">   	
						<div class="profile_section">
							<table id = "table">
								<tr >
									<th >Vehicle Manufacturer</th>
									<th >Sold in Past 30 Days</th>
									<th >Sold in Past Year</th>
                                    <th >Sold Over All Time</th>
								</tr>
                               
                                <?php								
                                    $query = "SELECT sale.ManufacturerName AS vehicle_manufacturer, SUM(sale.previous_30_days) AS thirty_days_sold_qty, SUM(sale.previous_1_year) AS one_year_sold_qty,SUM(sale.over_all_time) AS all_time_sold_qty FROM ( SELECT m.ManufacturerName, CASE WHEN MIN(PurchaseDate) between date_add(current_date, INTERVAL -30 DAY) and current_date Then 1 ELSE 0 END AS previous_30_days, CASE WHEN MIN(PurchaseDate) between date_add(current_date, INTERVAL -1 YEAR) and current_date Then 1 ELSE 0 END AS previous_1_year, CASE WHEN MIN(PurchaseDate) <= current_date Then 1 ELSE 0 END AS over_all_time FROM Manufacturer AS m JOIN Vehicle AS v ON m.ManufacturerID = v.ManufacturerID JOIN SalesRecord AS s ON v.VIN = s.VIN ) AS sale GROUP BY 1 ORDER BY 1 ASC";
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: view sale by manufacturer <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['vehicle_manufacturer']} </td>";
                                        print "<td>{$row['thirty_days_sold_qty']}</td>";
                                        print "<td>{$row['one_year_sold_qty']}</td>";
                                        print "<td>{$row['all_time_sold_qty']}</td>";
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