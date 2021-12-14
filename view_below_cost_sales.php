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
                <div class="title_name">View Below Cost Sales</div>

                <div class="features">   	
						<div class="profile_section">
							<table id = "table">
								<tr >
									<th >VIN</th>
									<th >Date</th>
									<th >Invoice Price</th>
                                    <th >Sold Price</th>
                                    <th >Ratio</th>
                                    <th >Customer</th>
                                    <th >SalesPerson</th>
								</tr>
                                <?php								
                                    $query = "SELECT s.VIN, PurchaseDate AS date, InvoicePrice AS invoice_price, SoldPrice AS sold_price, ROUND(SoldPrice/InvoicePrice, 2) AS ratio, CASE WHEN i.FirstName is not null THEN i.FirstName WHEN b.BusinessName is not null THEN b.BusinessName ELSE null END AS customer_first_name, concat(u.FirstName,' ' , u.LastName) AS salesperson_name, CASE WHEN SoldPrice/InvoicePrice <= 0.95 Then 1 ELSE 0 END AS red_ind FROM Vehicle AS v JOIN SalesRecord AS s ON v.VIN = s.VIN LEFT JOIN Individual AS i ON s.CustomerID=i.CustomerID LEFT JOIN Business AS b ON s.CustomerID = b.CustomerID JOIN SalesPerson AS sp ON sp.UserName = s.UserName JOIN User AS u ON sp.UserName = u.UserName ORDER BY 2 DESC, 5 DESC;";                      
                                    
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: view repairs by manufacturer <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                       
                                        if($row['red_ind'] == "1" ){
                                            print "<tr style='background-color: #FF0000'>";
                                            print "<td>{$row['VIN']} </td>";
                                            print "<td>{$row['date']}</td>";
                                            print "<td>{$row['invoice_price']}</td>";
                                            print "<td>{$row['sold_price']}</td>";
                                            print "<td>{$row['ratio']}</td>";
                                            print "<td>{$row['customer_first_name']}</td>";
                                            print "<td>{$row['salesperson_name']}</td>";
                                            print "</tr>";
                                        } else if ($row['red_ind'] == "0"){
                                            print "<tr >";
                                            print "<td>{$row['VIN']} </td>";
                                            print "<td>{$row['date']}</td>";
                                            print "<td>{$row['invoice_price']}</td>";
                                            print "<td>{$row['sold_price']}</td>";
                                            print "<td>{$row['ratio']}</td>";
                                            print "<td>{$row['customer_first_name']}</td>";
                                            print "<td>{$row['salesperson_name']}</td>";
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