
        <div id="header">
            <div class="logo">
            <img src="img/gtonline_logo.png" width = 100% style="opacity:0.5;background-color:E9E5E2;" border="0" alt="" title="GT Online Logo"/>
            </div>
        </div>

			<div class="nav_bar">
				<ul>
                    <li><a href="search_vehicle.php" <?php if($current_filename=='search_vehicle.php') {echo "class='active'";} $_SESSION['search_type'] = 'unsold'; ?>>Search Unsold</a></li>
					<li><a href="search_vehicle.php" <?php if(strpos($current_filename, 'search_vehicle.php') !== false) {echo "class='active'";} $_SESSION['search_type'] = 'sold'; ?>>Search Sold</a></li>
                    <li><a href="search_vehicle.php" <?php if($current_filename=='search_vehicle.php') echo "class='active'"; $_SESSION['search_type'] = 'all'; ?>>Search All</a></li>
                    <li><a href="add_vehicle.php" <?php if($current_filename=='add_vehicle.php') echo "class='active'"; ?>>Add Vehicle</a></li>
                    <li><a href="open_repair.php" <?php if($current_filename=='open_repair.php') echo "class='active'"; ?>>Open Repair</a></li>
                    <li><a href="view_report.php" <?php if($current_filename=='view_report.php') echo "class='active'"; ?>>View Report</a></li>
                    <li><a href="logout.php" <span class='glyphicon glyphicon-log-out'></span> Log Out</a></li>
				</ul>
			</div>i

