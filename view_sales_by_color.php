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
                <div class="title_name">View Sales by Color</div>

                <div class="features">   	
						<div class="profile_section">
							<table id = "table">
								<tr >
									<th >Vehicle Color</th>
									<th >Sold in Past 30 Days</th>
									<th >Sold in Past Year</th>
                                    <th >Sold Over All Time</th>
								</tr>
                               
                                <?php								
                                    $query = "SELECT color AS vehicle_color, SUM(previous_30_days) AS thirty_days_sold_qty, SUM(previous_1_year)".
                                    "AS one_year_sold_qty, SUM(over_all_time) AS all_time_sold_qty FROM ( SELECT s.VIN, CASE WHEN s.PurchaseDate BETWEEN DATE_ADD".
                                    "(CURRENT_DATE, INTERVAL -30 DAY) AND CURRENT_DATE THEN 1 ELSE 0 END AS previous_30_days, CASE WHEN s.PurchaseDate BETWEEN DATE_ADD".
                                    "(CURRENT_DATE, INTERVAL -1 YEAR) AND CURRENT_DATE THEN 1 ELSE 0 END AS previous_1_year, CASE WHEN s.PurchaseDate <= CURRENT_DATE THEN 1 ".
                                    "ELSE 0 END AS over_all_time, vc.color FROM SalesRecord AS s JOIN( SELECT VIN, CASE WHEN color_cnt > 1 THEN 'Multiple' ELSE clr END AS color ".
                                    "FROM ( SELECT VIN, COUNT(DISTINCT ColorDescription) AS color_cnt, MIN(ColorDescription) AS clr FROM ( SELECT vc.VIN, c.ColorDescription FROM ".
                                    "VehicleColor AS vc JOIN Color AS c ON vc.ColorID=c.ColorID) t GROUP BY VIN) t2) vc ON vc.VIN = s.VIN) AS sale GROUP BY 1 ORDER BY 1 ASC";
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: view sale by color <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['vehicle_color']} </td>";
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