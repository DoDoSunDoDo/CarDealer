<html>
<body>
<?php 
include('lib/common.php');
include("lib/header.php");
if (!isset($_SESSION['username'])) {
	header('Location: display_main_page.php');
	exit();
}
$vin = mysqli_real_escape_string($db, $_GET['vin']); 
$_SESSION['vin'] = $vin;
if (empty($_SESSION['vin'])) {
	header('Location: open_repair.php');
}

if( $_SERVER['REQUEST_METHOD'] == 'GET') {
    $query1 = "SELECT SalesID FROM salesrecord AS s WHERE s.VIN = '$vin'";
    $result = mysqli_query($db, $query1);
    $count = mysqli_num_rows($result);
    $_SESSION['eligible'] = $count;
    
    $query_typeTable = "CREATE TABLE vehicle_type_repair AS SELECT
        CASE 
            WHEN '$vin' IN (SELECT VIN FROM car) THEN 'Car'
            WHEN '$vin' IN (SELECT VIN FROM truck) THEN 'Truck'
            WHEN '$vin' IN (SELECT VIN FROM van) THEN 'Van'
            WHEN '$vin' IN (SELECT VIN FROM suv) THEN 'SUV'
            WHEN '$vin' IN (SELECT VIN FROM convertible) THEN 'Convertible'
        END AS VehicleType";
    mysqli_query($db, $query_typeTable);
    $query_type = "SELECT VehicleType FROM vehicle_type_repair";
    $type_result = mysqli_query($db, $query_type);
    if (mysqli_num_rows($type_result) > 0){
        $row = mysqli_fetch_assoc($type_result);
    }
    echo $row['VehicleType'];
    $vehicle_type = $row['VehicleType'];
    $query_drop = "DROP TABLE vehicle_type_repair";
    mysqli_query($db, $query_drop);
    
    $query2 = "SELECT v.VIN, v.ModelYear, m.ManufacturerName, v.ModelName,
    GROUP_CONCAT(c.ColorDescription SEPARATOR ', ') as colors
    FROM (SELECT Vehicle.*
    FROM Vehicle
    INNER JOIN SalesRecord
    ON Vehicle.VIN = SalesRecord.VIN
    ) v
    INNER JOIN Manufacturer AS m
    ON v.ManufacturerID = m.ManufacturerID
    INNER JOIN VehicleColor AS vc
    ON vc.VIN = v.VIN
    INNER JOIN Color AS c
    ON vc.ColorID = c.ColorID
    WHERE v.VIN = '$vin'";
    $result_vehicle = mysqli_query($db,$query2);

    $query3 = "SELECT
    r.VIN,
    r.RepairID,
    r.StartDate,
    r.CompleteDate,
    r.OdometerReading,
    r.LaborCharge,
    r.RepairDescription,
    ROUND(SUM(
        Parts.PartPrice * Parts.PartQuantity
    ),2) AS PartsCost,
    ROUND(
        r.LaborCharge + SUM(
            Parts.PartPrice * Parts.PartQuantity
        ),
        2
    ) AS TotalCost,
    r.CustomerID
    FROM
    (SELECT
        *
    FROM
        RepairRecord
    WHERE
        VIN = '$vin'
    ) AS r
    LEFT JOIN Parts ON r.RepairID = Parts.RepairID
    GROUP BY
    1,
    2,
    3,
    4,
    5,
    6,
    7
    ORDER BY
    r.CompleteDate";

    $result_record = mysqli_query($db,$query3);
}
include('lib/show_queries.php');
?>
    <div id="main_container">
		<div class = "center_content">
			<div class = "title">Repair Records</div>
            <div>
                <?php
                    if ($_SESSION['eligible']==0){
                    print "The vehicle is not eligible for repairing";
                    }
                ?>
            </div>
            <table>
                <tr>
                    <td class=heading>VIN</td>
                    <td class=heading>Vehicle Type</td>
                    <td class=heading>Model Year</td>
                    <td class=heading>Manufacturer</td>
                    <td class=heading>Model</td>
                    <td class=heading>Color</td>
                </tr>
                <?php
                    $row1 = mysqli_fetch_array($result_vehicle, MYSQLI_NUM);
                    print "<tr>";
                    print "<td>$row1[0]</td>";
                    print "<td>$vehicle_type</td>";
                    print "<td>$row1[1]</td>";
                    print "<td>$row1[2]</td>";
                    print "<td>$row1[3]</td>"; 
                    print "<td>$row1[4]</td>";
                    print"</tr>";
                ?>    
			</table>
            <table>
                <tr>
                    <td class=heading>Repair ID</td>
                    <td class=heading>Customer ID</td>
                    <td class=heading>Start Date</td>
                    <td class=heading>Complete Date</td>
                    <td class=heading>Odometer Reading</td>
                    <td class=heading>Labor Charge</td>
                    <td class=heading>Description</td>
                    <td class=heading>Parts Cost</td>
                    <td class=heading>Total Cost</td>
                </tr>
                <?php
                    $_SESSION['editable'] = FALSE;
                    $_SESSION['addable'] = TRUE;
                    while($row2 = mysqli_fetch_array($result_record, MYSQLI_NUM)){
                        print "<tr>";
                        print "<td>$row2[1]</td>";
                        print "<td>$row2[9]</td>";
                        print "<td>$row2[2]</td>";
                        print "<td>$row2[3]</td>";
                        if (!isset($row2[3])) {
                            $_SESSION['repair_id'] = $row2[1];
                            $_SESSION['editable'] = TRUE;
                            $_SESSION['addable'] = FALSE;
                        }
                        print "<td>$row2[4]</td>";
                        print "<td>$row2[5]</td>";
                        print "<td>$row2[6]</td>"; 
                        if (!isset($row2[7])){
                            print "<td>0</td>";
                            print "<td>$row2[5]</td>";
                        }
                        else {
                            print "<td>$row2[7]</td>";
                            print "<td>$row2[8]</td>";
                        }
                        print"</tr>";
                    }
                ?>    
			</table>
            <br></br>
            <form action="search_customer4repair.php" method="get">
                <input type="submit" value="ADD">
            </form>
            <form action="edit_repair_record.php" method="get">
                <input type="submit" value="EDIT">
            </form>
                
		</div>
	</div>
	<?php include("lib/error.php"); ?>
    <div class="clear"></div>
</body>
</html>


