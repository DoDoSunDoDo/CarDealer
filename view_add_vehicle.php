<?php 
include('lib/common.php');

// constraint: page must be directed to after log in
if (!isset($_SESSION['username'])) {
	header('Location: display_main_page.php');
	exit();
}
?>

<?php include("lib/header.php"); ?>
<head>
<title>View Add Vehicle</title>
</head>

<body>
    <div id="main_container">
    <div class="center_content" height: 800px;>

<?php
    if(isset($_POST['save'])){
        // check model year constraint
        if ($_POST['ModelYear'] <= date('Y') + 1){
            $UserName = $_SESSION['username'];
            $_SESSION['vin']= $_POST['VIN'];  # session var is not case-sensitive
            $ModelYear = $_POST['ModelYear'];
            $ModelName = $_POST['ModelName'];
            $AddDate = $_POST['AddDate']; 
            $InvoicePrice = $_POST['InvoicePrice'];
            $_SESSION['type'] = $_POST['VehicleType'];
            $AdditionalInfo = $_POST['AdditionalInfo'];
            $ManufacturerName = $_POST['ManufacturerName'];
            $colors = explode(', ', $_POST['colors']);

            $query_Manufacturer = "SELECT ManufacturerID FROM Manufacturer WHERE ManufacturerName = '$ManufacturerName'";
            $result_Manufacturer = mysqli_query($db, $query_Manufacturer);
            while ($row = mysqli_fetch_array($result_Manufacturer)){
                $ManufacturerID = $row[0];
            }
        
            $sql = "INSERT INTO Vehicle (VIN, UserName, ModelYear, AddDate, InvoicePrice, ModelName, ManufacturerID, AdditionalInfo) VALUES ('{$_SESSION['vin']}', '$UserName', {$ModelYear}, '$AddDate', {$InvoicePrice},'$ModelName', {$ManufacturerID}, '$AdditionalInfo')";
            $result = mysqli_query($db, $sql);

            if (!empty($result)) {
                echo "<br>New vehicle added!</br>";
                } else {
                echo "Error: " . $sql . "
                    " . mysqli_error($db);
                }

            $i=0;
            while ($i<count($colors)){
                $sql_color = "INSERT INTO VehicleColor (VIN, ColorID) SELECT '{$_SESSION['vin']}', ColorID FROM Color WHERE LOWER(ColorDescription) = LOWER('$colors[$i]')";
                $result = mysqli_query($db, $sql_color);
                $i+=1;
            }

            if (!empty($result)) {
                echo "<br>New vehicle color added!</br>";
                } else {
                echo "Error: " . $sql_color . "
                    " . mysqli_error($db);
                }
        } else{
            echo "Error: model year is not valid, note that it cannot be bigger than current year + 1!";
        }
        
    }
?>
<?php
        if ($_SESSION['type']=='Car'){
            print "<form action='view_add_vehicle.php' method='post'>
            <label>Number Of Doors: </label>
            <input type='int' name='numofdoors' value=''/><br>
            <input type='submit' name='add_vehicle_type' value='SAVE'/><br>
            </form><br>";
        }
        if ($_SESSION['type']=='SUV'){
            print "<form action='view_add_vehicle.php' method='post'>
            <label>Drive Train Type: </label>
            <input type='text' name='drivetraintype' value=''/><br>
            <label>Number Of Cupholders: </label>
            <input type='int' name='numberofcupholders' value=''/><br>
            <input type='submit' name='add_vehicle_type' value='SAVE'/><br>
            </form><br>";
        }
        if ($_SESSION['type']=='Truck'){
            print "<form action='view_add_vehicle.php' method='post'>
            <label>Cargo Capacity: </label>
            <input type='int' name='cargocapacity' value=''/><br>
            <label>Cargo Cover Type: </label>
            <input type='text' name='cargocovertype' value=''/><br>
            <label>Number Of Rear Axis: </label>
            <input type='int' name='numberofrearaxis' value=''/><br>
            <input type='submit' name='add_vehicle_type' value='SAVE'/><br>
            </form><br>";
        }
        if ($_SESSION['type']=='Van'){
            print "<form action='view_add_vehicle.php' method='post'>
            <label>Has Driver Side Back Door: </label>
                <select name='hasdriversidebackdoor'>
                <option value='Yes'>Yes</option>
                <option value='No'>No</option>
                </select>  
            <input type='submit' name='add_vehicle_type' value='SAVE'/><br>
            </form><br>";
        }
        if ($_SESSION['type']=='Convertible'){
            print "<form action='view_add_vehicle.php' method='post'>
            <label>Roof of Type: </label>
            <input type='text' name='rooftype' value=''/><br>
            <label>Back Seat Count: </label>
            <input type='text' name='backseatcount' value=''/><br>
            <input type='submit' name='add_vehicle_type' value='SAVE'/><br>
            </form><br>";
        }

        if (isset($_POST['add_vehicle_type'])){
    
            if ($_SESSION['type']=='Car'){
                $sub_sql = "INSERT INTO Car (VIN, NumberOfDoors) VALUES('{$_SESSION['vin']}', {$_POST['numofdoors']})"; 
                $sub_result = mysqli_query($db, $sub_sql); 
            } elseif ($_SESSION['type']=='SUV'){
                $sub_sql = "INSERT INTO SUV (VIN, DrivetrainType, NumberOfCupholders) VALUES('{$_SESSION['vin']}', '{$_POST['drivetraintype']}', {$_POST['numberofcupholders']})"; 
                $sub_result = mysqli_query($db, $sub_sql); 
            } elseif ($_SESSION['type']=='Truck'){
                $sub_sql = "INSERT INTO Truck (VIN, CargoCapacity, CargoCoverType, NumberOfRearAxis) VALUES('{$_SESSION['vin']}', {$_POST['cargocapacity']}, '{$_POST['cargocovertype']}', {$_POST['numberofrearaxis']})"; 
                $sub_result = mysqli_query($db, $sub_sql); 
            } elseif ($_SESSION['type']=='Van'){
                $sub_sql = "INSERT INTO Van (VIN, HasDriverSideBackDoor) VALUES('{$_SESSION['vin']}', '{$_POST['hasdriversidebackdoor']}')"; 
                $sub_result = mysqli_query($db, $sub_sql); 
            } elseif ($_SESSION['type']=='Convertible'){
                    $sub_sql = "INSERT INTO Convertible (VIN, RoofType, BackSeatCount) VALUES('{$_SESSION['vin']}', '{$_POST['rooftype']}', {$_POST['backseatcount']})"; 
                    $sub_result = mysqli_query($db, $sub_sql); 
            }



            if (!empty($sub_result)) {
                echo "New vehicle type related added!";
                // recreate vehicle detail table
                include('lib/vehicle_detail.php');
                // jump in to view vehicle detaiil page after adding
                header("Refresh:1; url=view_vehicle_detail.php?vin=" . $_SESSION['vin'] . "?type=". $_SESSION['type']. ".php");
                } else {
                    echo "Error: " . $sub_sql . "
                        " . mysqli_error($db);
                    }
        }

?>

</body>