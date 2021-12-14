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
                <div class="title_name">View Gross Customer Income</div>

                <div class="features">   	
						<div class="profile_section">
                        <form method="post">
							<table id = "table">
								<tr >
									<th >Customer Name</th>
									<th >Sales Number</th>
									<th >Repair Times</th>
                                    <th >First Income Day</th>
                                    <th >Recent Income Day</th>
                                    <th >Total Income</th>
								</tr>
                               
                                <?php								
                                    $query = "SELECT CASE WHEN i.CustomerID is not null THEN i.CustomerID ELSE b.CustomerID END AS customer_id, CASE WHEN i.CustomerID is not null THEN i.FirstName ELSE b.BusinessName END AS customer_name, nbr_sales, nbr_repairs, min_spend_date, max_spend_date, ttl_spend FROM (SELECT s.CustomerID, nbr_sales, nbr_repairs, CASE WHEN min_sale_date>=min_start_date THEN min_start_date ELSE min_sale_date end AS min_spend_date, CASE WHEN max_sale_date<=max_start_date THEN max_start_date ELSE max_sale_date END AS max_spend_date, ROUND(SUM(s.sold_price)+SUM(rp.repair_cost)) AS ttl_spend FROM ( SELECT CustomerID, COUNT(SalesID) as nbr_sales, MIN(PurchaseDate) as min_sale_date, MAX(PurchaseDate) as max_sale_date, SUM(SoldPrice) as sold_price FROM SalesRecord GROUP BY 1) s LEFT JOIN ( SELECT CustomerID, MIN(StartDate) as min_start_date, MAX(StartDate) as max_start_date, COUNT(RepairID) AS nbr_repairs, SUM(LaborCharge)+SUM(parts_cost) as repair_cost FROM (SELECT r.*, COALESCE(p.parts_cost,0) as parts_cost FROM RepairRecord r LEFT JOIN (SELECT RepairID, SUM(PartQuantity*PartPrice) AS parts_cost FROM Parts GROUP BY 1) p ON r.RepairID = p.RepairID ) t GROUP BY 1 ) rp ON s.CustomerID=rp.CustomerID GROUP BY 1,2,3,4,5 Order by 6 desc LIMIT 15 ) AS spend LEFT JOIN Individual i ON spend.CustomerID = i.CustomerID LEFT JOIN Business b ON spend.CustomerID = b.CustomerID";                      
                                    
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: view gorss income <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        $_SESSION['customer_name'] = ''; // reset to default setting
                                        $_SESSION['customer_id'] = '';
                                        $customer_name = urlencode($row['customer_name']);
                                        $customer_id = urlencode($row['customer_id']);
                                        print "<tr>";
                                        print "<td><a href='customer_income_detail.php?customer_name=$customer_name?customer_id=$customer_id'>{$row['customer_name']}</a></td>";
                                        print "<td>{$row['nbr_sales']}</td>";
                                        print "<td>{$row['nbr_repairs']}</td>";
                                        print "<td>{$row['min_spend_date']}</td>";
                                        print "<td>{$row['max_spend_date']}</td>";
                                        print "<td>{$row['ttl_spend']}</td>";                                       
                                        print "</tr>";							
                                    }								
                                ?>
							</table>
                        </form>
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