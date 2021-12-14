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
                <div class="title_name">View Parts Statistics</div>

                <div class="features">   	
						<div class="profile_section">
							<table id = "table">
								<tr >
									<th >PartVendor</th>
									<th >Total Parts Quantity</th>
                                    <th >Total Parts Price</th>
								</tr>
                               
                                <?php								
                                    $query = "SELECT PartVendor, SUM(PartQuantity) AS total_parts_qty, ROUND(SUM(PartPrice*PartQuantity)) AS total_parts_price FROM Parts AS p GROUP BY 1;";
                                    ;
                                    $result = mysqli_query($db, $query);
                                     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: view Average Time in Inventory <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['PartVendor']} </td>";
                                        print "<td>{$row['total_parts_qty']}</td>";
                                        print "<td>{$row['total_parts_price']}</td>";
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