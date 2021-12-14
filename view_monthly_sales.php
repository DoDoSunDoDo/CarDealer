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
                <div class="title_name">View Monthly Sales</div>

                <div class="features">   	
						<div class="profile_section">
							<table id = "table">
								<tr >
									<th >Month</th>
									<th >Vehicle Sold Number</th>
                                    <th >Total Sales Income</th>
                                    <th >Total Net Income</th>
                                    <th >Sold/Invoice Price Ratio</th>
								</tr>
                               
                                <?php								
                                    $query = "SELECT SUBSTRING(CAST(sr.PurchaseDate as char), 1, 7) AS 'year_month', COUNT(sr.VIN) AS total_vehicle_sold, ROUND(SUM(sr.SoldPrice)) AS total_sales_income, ROUND(SUM(sr.SoldPrice - v.InvoicePrice)) AS total_net_income, ROUND((SUM(sr.SoldPrice)/SUM(v.InvoicePrice))*100, 2) AS sold_cost_ratio, CASE WHEN SUM(sr.SoldPrice)/SUM(v.InvoicePrice) >= 1.25 THEN 'Green' WHEN SUM(sr.SoldPrice)/SUM(v.InvoicePrice) <= 1.1 THEN 'Yellow' ELSE NULL END AS 'highlight_ind' FROM SalesRecord sr INNER JOIN Vehicle v ON sr.VIN = v.VIN GROUP BY 1 ORDER BY 1 DESC;";       
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: view Average Time in Inventory <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        $_SESSION['month'] = ''; // reset to default setting
                                        $month = urlencode($row['year_month']);

                                        if($row['sold_cost_ratio'] <110 ){
                                            print "<tr>";
                                            print "<td><a href='monthly_sale_detail.php?month=$month'>{$row['year_month']}</a> </td>";
                                            print "<td>{$row['total_vehicle_sold']}</td>";
                                            print "<td>{$row['total_sales_income']}</td>";
                                            print "<td>{$row['total_net_income']}</td>";
                                            print "<td>{$row['sold_cost_ratio']}</td>";
                                            print "</tr>";		
                                        }
                                        else if($row['sold_cost_ratio'] >= 110 AND  $row['sold_cost_ratio'] < 125){
                                            print "<tr style='background-color: #FFFF00'>";
                                            print "<td><a href='monthly_sale_detail.php?month=$month'>{$row['year_month']}</a> </td>";
                                            print "<td>{$row['total_vehicle_sold']}</td>";
                                            print "<td>{$row['total_sales_income']}</td>";
                                            print "<td>{$row['total_net_income']}</td>";
                                            print "<td>{$row['sold_cost_ratio']}</td>";
                                            print "</tr>";		
                                        } 
                                        else{
                                            print "<tr style='background-color: #00FF00'>"; 
                                            print "<td><a href='monthly_sale_detail.php?month=$month'>{$row['year_month']}</a> </td>";
                                            print "<td>{$row['total_vehicle_sold']}</td>";
                                            print "<td>{$row['total_sales_income']}</td>";
                                            print "<td>{$row['total_net_income']}</td>";
                                            print "<td>{$row['sold_cost_ratio']}</td>";
                                            print "</tr>";		
                                        }			
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