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
        <div class="center_content" height: 800px;>
            <div class="center_left">
                <div class="title_name">View Report List</div>

				<div class="features">
					<div class="profile_section">
						<a href="view_sales_by_color.php">
							<div class="subtitle">View Sales by Color</div>
						</a>
						<a href="view_sales_by_type.php">
							<div class="subtitle">View Sales by Type</div>
						</a>
						<a href="view_sales_by_manufacturer.php">
							<div class="subtitle">View Sales by Manufacturer</div>
						</a>
						<a href="view_gross_customer_income.php">
							<div class="subtitle">View Gross Customer Income</div>
						</a>
						<a href="view_repairs_by_manufacturer.php">
							<div class="subtitle">View Repairs by Manufacturer</div>
						</a>
						<a href="view_below_cost_sales.php">
							<div class="subtitle">View Below Cost Sales</div>
						</a>
						<a href="view_average_time_in_inventory.php">
							<div class="subtitle">View Average Time in Inventory</div>
						</a>
						<a href="view_parts_statistics.php">
							<div class="subtitle">View Parts Statistics</div>
						</a>
						<a href="view_monthly_sales.php">
							<div class="subtitle">View Monthly Sales</div>
						</a>
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

