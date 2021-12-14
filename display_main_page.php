<?php
// read common setting
include('lib/common.php');
if($showQueries){
  array_push($query_msg, "showQueries currently turned ON, to disable change to 'false' in lib/common.php");
}
// remove all sessions
session_destroy();

// create vehicle detail table
include('lib/vehicle_detail.php');
?>

<?php include("lib/header.php");?>
<head>
<title>Main Page</title>
</head>

<html>
<body>
    <div id="main_container">
        <div class="center_content">
            <div class="text_box">
                <div class='title'> Welcome to  Jaunty Jalopies </div>
                    <!-- calculate inventory volume -->
                    <div class="heading">
                        <?php

                            $_SESSION['username'] = ""; // default setting as non log-in
                            $_SESSION['search_type'] = ""; // default setting as null, doing unsold search
                            $query = "SELECT COUNT(v.VIN) AS inventory_vehicle
                            FROM vehicle AS v
                            LEFT JOIN salesrecord AS s
                            ON v.VIN = s.VIN
                            WHERE s.VIN is NULL";
                            $result = mysqli_query($db, $query);
                            include('lib/show_queries.php');

                            if (!empty($result)) {
                                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                print '<br></br>';
                                print 'Inventory Volume: '. $row['inventory_vehicle'];
                            } else {
                                array_push($error_msg,  "Query ERROR: Failed to get inventory vehcile volume...<br>" . __FILE__ ." line:". __LINE__ );
                            }
                        ?>
                    </div>
                    <br></br>
                    <table>
                        <tr>
                            <td><a  href="login.php"><button>LOG IN</button></a></td>
                            <td><a  href="search_vehicle.php"><button>SEARCH</button></a></td>
                        </tr>
                    </table>
                </div>
                <?php include("lib/error.php"); ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>
