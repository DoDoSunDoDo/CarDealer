<?php 
include('lib/common.php');
include("lib/header.php");
include('lib/show_queries.php');
// constraint: page must be directed to after log in
if (!isset($_SESSION['username'])) {
	header('Location: display_main_page.php');
	exit();
}
?>

<head>
<title>Open Repair Page</title>
</head>

<html>
<body>
	<div id="main_container">
		<div class = "center_content">
			<div class = "text_box">
				<form action = "view_vehicle_repair.php" method = "get" enctype = "multipart/form-data">
					<div class="title">Repair Record Search</div>
					<div class="login_form_row">
						<label class="login_label">VIN#</label>
						<input type="text" name="vin" value="" class="login_input"/>
					</div>
					<div>
						<a href="view_vehicle_repair.php" style = "float:right;"><button type="submit" name="search">SEARCH</button></a>	
					</div>
				</form>
				<a  href="update_main_page.php" style="float: right;"><button>MAIN PAGE</button></a>	
			</div>
			<?php include("lib/error.php"); ?>
		<div class="clear"></div>
	</div>
</body>
</html>
