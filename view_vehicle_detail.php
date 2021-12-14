<?php include('lib/common.php');
include("lib/header.php");
?>

<head>
<title>View Vehicle Detail Page</title>
</head>

<?php
    if(!isset($_SESSION['vin']) or empty($_SESSION['vin'])) {
        // below setting is a temporary workaround
        $_SESSION['vin'] = explode("?", $_GET['vin'], 2)[0];
        $_SESSION['type'] = strtolower(explode("=", $_GET['vin'], 2)[1]);
        if ($debug) {
            echo '<br> Test #: 1 </br>';
            echo '<pre> All session assigned are: </pre>';
            echo print_r($_SESSION);
        }
    }
                    
?>

<!-- extract columns from VIEW depends on role access -->
<?php
    // vehicle_detail has 0-36 fixed index
    $query1 = "SELECT DISTINCT VIN_1,
    VehicleType,
    ModelYear, 
    ManufacturerName,
    ModelName,
    Colors,
    ListPrice,
    AdditionalInfo,
    InvoicePrice,
    AddDate,
    ClerkName,
    VIN_2,
    SoldPrice,
    PurchaseDate,
    CustomerID,
    City,
    State,
    PostalCode,
    Street,
    Email,
    PhoneNumber,
    PersonalName,
    PrimaryContactName,
    BusinessName,
    PrimaryContactTitle,
    SalesName, a.* 
    FROM (SELECT * FROM vehicle_detail as v WHERE VIN_1 = '{$_SESSION['vin']}') as v
    LEFT JOIN {$_SESSION['type']} as a
    ON v.VIN_1 = a.VIN";
    $result1 = mysqli_query($db, $query1);
    $result3 = mysqli_query($db, $query1); 
    $query2 = "SELECT VIN_3,
    StartDate,
    CompleteDate,
    RepairDescription,
    OdometerReading,
    LaborCharge,
    PartsCost,
    TotalCost,
    CustomerID_2,
    CustomerName,
    ServiceWriterName 
    FROM vehicle_detail WHERE VIN_3 = '{$_SESSION['vin']}'";
    $result2 = mysqli_query($db, $query2); 
    $type_attr_col = mysqli_query($db, "SHOW COLUMNS FROM {$_SESSION['type']}");  
    $type_attr_count = mysqli_num_rows($type_attr_col);
?>          

<html>
<body>
    <div id='main_container'>
        <div class="center_content">
            <div class="title">Vehicle Detail</div>  
            <?php
                if (!empty($result1)){
                    print "<table>";
                        print "<tr>";
                            while($row = mysqli_fetch_array($type_attr_col, MYSQLI_ASSOC)){
                                print "<td class=heading>". $row['Field']. "</td>";
                            }
                            print "<td class=heading>Vehicle Type</td>";
                            print "<td class=heading>Model Year</td>";
                            print "<td class=heading>Manufacturer</td>";
                            print "<td class=heading>Model</td>";
                            print "<td class=heading>Color</td>";
                            print "<td class=heading>List Price</td>";
                            print "<td class=heading>Description</td>";
                            if ($_SESSION['clerk'] or $_SESSION['manager']) {
                                print "<td class=heading>Invoice Price</td>"; 
                            }
                            if ($_SESSION['manager']) {
                                print "<td class=heading>Add Date</td>";  
                                print "<td class=heading>Added By</td>"; 
                            } 
                        print "</tr>";
                        print "<tr>";
                            $col = $type_attr_start = 27;
                            while($row = mysqli_fetch_array($result1, MYSQLI_NUM)){
                                print "<td>$row[0]</td>";
                                while($col < $type_attr_start + $type_attr_count - 1){
                                    print "<td>$row[$col]</td>";
                                    $col+=1;
                                } 
                                print "<td>$row[1]</td>"; 
                                print "<td>$row[2]</td>";
                                print "<td>$row[3]</td>";
                                print "<td>$row[4]</td>";
                                print "<td>$row[5]</td>";
                                print "<td>$row[6]</td>";
                                print "<td>$row[7]</td>";
                                if ($_SESSION['clerk'] or $_SESSION['manager']) {
                                    print "<td>$row[8]</td>"; 
                                }
                                if ($_SESSION['manager']) {
                                    print "<td>$row[9]</td>";
                                    print "<td>$row[10]</td>"; 
                                }
                            }
                        print "</tr>";
                    print "</table>";
                } else {
                    print 'Error occurred in the query';
                }
            ?>
            <pre></pre>
            <div class="title">Sales Detail</div>  
            <?php
                if (!empty($result3) && isset($_SESSION['manager'])) {
                    print "<table>";
                        print "<tr>";
                            print "<td class=heading>Sold Price</td>";
                            print "<td class=heading>Sold Date</td>";
                            print "<td class=heading>City</td>";
                            print "<td class=heading>State</td>";
                            print "<td class=heading>PostalCode</td>";
                            print "<td class=heading>Street</td>";
                            print "<td class=heading>Email</td>";
                            print "<td class=heading>Phone Number</td>";
                            print "<td class=heading>Peronsal Name</td>";
                            print "<td class=heading>Primary Contact Name</td>";
                            print "<td class=heading>Business Name</td>";
                            print "<td class=heading>Primary Contact Title</td>";
                            print "<td class=heading>Sold By</td>";
                        print "</tr>";
                        print "<tr>";
                            while($row = mysqli_fetch_array($result3, MYSQLI_NUM)){
                                print "<td>$row[12]</td>"; 
                                print "<td>$row[13]</td>";
                                print "<td>$row[15]</td>";
                                print "<td>$row[16]</td>";
                                print "<td>$row[17]</td>";
                                print "<td>$row[18]</td>";
                                print "<td>$row[19]</td>";
                                print "<td>$row[20]</td>";
                                print "<td>$row[21]</td>";
                                print "<td>$row[22]</td>";
                                print "<td>$row[23]</td>";
                                print "<td>$row[24]</td>"; 
                                print "<td>$row[25]</td>"; 
                            }
                        print "</tr>";
                    print "</table>";
                } else {
                    print 'Internal Information';
                }
            ?>
            <pre></pre>
            <div class="title">Repair Detail</div>  
            <?php
                if (!empty($result2) && isset($_SESSION['manager'])) {
                    print "<table>";
                        print "<tr>";
                            print "<td class=heading>Start Date</td>";
                            print "<td class=heading>Complete Date</td>";
                            print "<td class=heading>Description</td>";
                            print "<td class=heading>Odometer Reading</td>";
                            print "<td class=heading>Labor Charge</td>";
                            print "<td class=heading>Parts Cost</td>";
                            print "<td class=heading>Total Cost</td>";
                            print "<td class=heading>Customer Name</td>";
                            print "<td class=heading>Repaired By</td>";
                        print "</tr>";
                        // $r = 0;
                        // $row_count = mysqli_num_rows($result2);
                        // echo '# of repair records are: '. $row_count;
                        while($row = mysqli_fetch_array($result2, MYSQLI_NUM)){
                            print "<tr>";
                            print "<td>$row[1]</td>"; 
                            print "<td>$row[2]</td>";
                            print "<td>$row[3]</td>";
                            print "<td>$row[4]</td>";
                            print "<td>$row[5]</td>";
                            print "<td>$row[6]</td>";
                            print "<td>$row[7]</td>";
                            print "<td>$row[9]</td>";
                            print "<td>$row[10]</td>";
                            print "</tr>";
                        }
                    print "</table>";
                } else {
                        print 'Internal Information';
                    }
            ?>


            <a  href="view_vehicle_list.php" style="float: right;"><button>BACK</button></a>
            
            <?php
                if ($_SESSION['salesperson']){
            ?>
                    <a  href="search_customer.php" style="float: right;"><button>SELL</button></a>
                <?php
                }
                ?>
        </div>
    </div>
</body>
<html>
