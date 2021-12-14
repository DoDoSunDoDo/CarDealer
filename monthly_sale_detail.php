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
$_SESSION['month'] = ''; // reset to default setting

    if(!isset($_SESSION['month']) or empty($_SESSION['month'])) {
        // below setting is a temporary workaround
        $_SESSION['month'] = explode("?", $_GET['month'], 2)[0];
        // $_SESSION['customer_id'] = explode("=", $_GET['customer_name'], 3)[1];
        if ($debug) {
            echo '<br> Test #: 1 </br>';
            echo '<br> Current customer_name being tracked is: '. $_SESSION['month']. '</br>';
            // echo '<br> Current customer_id being tracked is: '. $_SESSION['customer_id']. '</br>';
        }
    }
                    
?>
<body>
    <div id="main_container">
        <div class="center_content">
            <div class="center_left">
                <div class="title_name">Sales Detail for <?php print $_SESSION['month']; ?> </div>
                <div class="features">   	
						<div class="profile_section">
                        <form method="post">
							<table id = "table">
								<tr >
									<th >Salesperson First Name</th>
									<th >Salesperson Last Name</th>
									<th >Sold Number</th>
                                    <th >Total Sales</th>
								</tr>
                                <?php
                                    $month = urlencode($_SESSION['month']);		
                                    $query = "SELECT u.FirstName AS salesperson_first_name, u.LastName AS salesperson_Last_name, sr.sold_vehicle_cnt AS sold_count, sr.sales AS total_sales FROM ( SELECT UserName, COUNT(VIN) AS sold_vehicle_cnt, ROUND(SUM(SoldPrice)) AS sales FROM SalesRecord WHERE substring(cast(PurchaseDate AS CHAR),1,7)= '$month' GROUP BY 1) sr INNER JOIN SalesPerson sp ON sr.UserName = sp.UserName INNER JOIN User u ON u.UserName = sp.UserName ORDER BY 3 DESC, 4 DESC;";                      
                                    
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: view gorss income <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['salesperson_first_name']}</a></td>";
                                        print "<td>{$row['salesperson_Last_name']}</td>";
                                        print "<td>{$row['sold_count']}</td>";
                                        print "<td>{$row['total_sales']}</td>";
                                        print "</tr>";							
                                    }	
                                ?>
							</table>
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