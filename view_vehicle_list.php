<?php include('lib/common.php');
include("lib/header.php");

if (isset($_POST['type_for_search'])){
    $_SESSION['keyword'] =  $_POST['keyword'];
    $_SESSION['type_for_search'] =  $_POST['type_for_search']; 
    $_SESSION['manufacturer'] =  $_POST['manufacturer'];
    $_SESSION['model_year'] =  $_POST['model_year'];
    $_SESSION['color'] =  $_POST['color'];
    $_SESSION['min_list_price'] =  $_POST['min_list_price'];
    $_SESSION['max_list_price'] =  $_POST['max_list_price'];
    $_SESSION['vin_for_search'] =  $_POST['vin_for_search']; 
}
?>

<head>
<title>View Vehicle List Page</title>
</head>

<!-- escape string for input variable -->
<?php
    //Note: known issue with _POST always empty using PHPStorm built-in web server: Use *AMP server instead
    $keyword = strtolower(mysqli_real_escape_string($db, $_SESSION['keyword'])); // case-sensitive
    $type = mysqli_real_escape_string($db, $_SESSION['type_for_search']);
    $manufacturer = mysqli_real_escape_string($db, $_SESSION['manufacturer']);
    $model_year = mysqli_real_escape_string($db, $_SESSION['model_year']);
    $color = mysqli_real_escape_string($db, $_SESSION['color']);
    $min_list_price = mysqli_real_escape_string($db, $_SESSION['min_list_price']);
    $max_list_price = mysqli_real_escape_string($db, $_SESSION['max_list_price']);
    if ($_SESSION['username'] != null) {
        $vin = mysqli_real_escape_string($db, $_SESSION['vin_for_search']);
    } else {
        $vin = 'All';
    }
?>

<!-- execute searching query -->
<!-- to avoid SQL injection when passing php vairables, use prepared statement -->
<!-- TODO: add check box for matched records -->
<?php 
    if ($debug) {
        echo '<br> Test #: 1  </br>';
        echo '<pre> All session assigned are: </pre>';
        echo print_r($_SESSION);
    }
    if (empty($_SESSION['search_type']) or $_SESSION['search_type'] == 'unsold'){
        $query = "SELECT DISTINCT VIN_1, VehicleType, ModelYear, ManufacturerName, ModelName, Colors, ListPrice,
        CASE WHEN ('$keyword' != '' AND (LOWER(ManufacturerName) LIKE '%$keyword%' OR LOWER(ModelYear) = '$keyword' OR
        LOWER(ModelName) LIKE '%$keyword%' OR LOWER(AdditionalInfo) LIKE '%$keyword%')) THEN  'Yes' ELSE 'No' END AS Keyword_Matched
        FROM vehicle_detail
        WHERE (
        VIN_2 is NULL AND VIN_3 is NULL  -- means no sold and repair record
        AND VehicleType = IF(? = 'All', VehicleType, ?)
        AND (ManufacturerName = IF(? = 'All', ManufacturerName, ?) OR (LOWER(ManufacturerName) LIKE '%$keyword%' AND '$keyword' != ''))
        AND (ModelYear = IF(? = 'All', ModelYear, ?) OR (LOWER(ModelYear) = '$keyword' AND '$keyword' != '')) -- match entire string for year
        AND ListPrice BETWEEN '$min_list_price' AND '$max_list_price'
        AND VIN_1 = IF(? = 'All', VIN_1, ?)
        AND Colors LIKE IF(? = 'All', Colors, '%$color%')
        )
        OR (LOWER(ModelName) LIKE '%$keyword%' AND '$keyword' != '') 
        OR (LOWER(AdditionalInfo) LIKE '%$keyword%' AND '$keyword' != '')
        ORDER BY VIN_1";
    } elseif ($_SESSION['search_type'] == 'sold'){
        $query = "SELECT DISTINCT VIN_1, VehicleType, ModelYear, ManufacturerName, ModelName, Colors, ListPrice, 
        CASE WHEN ('$keyword' != '' AND (LOWER(ManufacturerName) LIKE '%$keyword%' OR LOWER(ModelYear) = '$keyword' OR
        LOWER(ModelName) LIKE '%$keyword%' OR LOWER(AdditionalInfo) LIKE '%$keyword')) THEN  'Yes' ELSE 'No' END AS Keyword_Matched
        FROM vehicle_detail
        WHERE (
        VIN_2 is NOT NULL  -- means sold
        AND VehicleType = IF(? = 'All', VehicleType, ?)
        AND (ManufacturerName = IF(? = 'All', ManufacturerName, ?) OR (LOWER(ManufacturerName) LIKE '%$keyword%' AND '$keyword' != ''))
        AND (ModelYear = IF(? = 'All', ModelYear, ?) OR LOWER(ModelYear) = '$keyword') -- match entire string for year
        AND ListPrice BETWEEN '$min_list_price' AND '$max_list_price'
        AND VIN_1 = IF(? = 'All', VIN_1, ?)
        AND Colors LIKE IF(? = 'All', Colors, '%$color%')
        )
        OR (LOWER(ModelName) LIKE '%$keyword%' AND '$keyword' != '') 
        OR (LOWER(AdditionalInfo) LIKE '%$keyword%' AND '$keyword' != '')
        ORDER BY VIN_1"; 
    } elseif ($_SESSION['search_type'] == 'all'){
        $query = "SELECT DISTINCT VIN_1, VehicleType, ModelYear, ManufacturerName, ModelName, Colors, ListPrice, 
        CASE WHEN ('$keyword' != '' AND (LOWER(ManufacturerName) LIKE '%$keyword%' OR LOWER(ModelYear) = '$keyword' OR
        LOWER(ModelName) LIKE '%$keyword%' OR LOWER(AdditionalInfo) LIKE '%$keyword')) THEN  'Yes' ELSE 'No' END AS Keyword_Matched
        FROM vehicle_detail
        WHERE (
        VehicleType = IF(? = 'All', VehicleType, ?)
        AND (ManufacturerName = IF(? = 'All', ManufacturerName, ?) OR (LOWER(ManufacturerName) LIKE '%$keyword%' AND '$keyword' != ''))
        AND (ModelYear = IF(? = 'All', ModelYear, ?) OR LOWER(ModelYear) = '$keyword') -- match entire string for year
        AND ListPrice BETWEEN '$min_list_price' AND '$max_list_price'
        AND VIN_1 = IF(? = 'All', VIN_1, ?)
        AND Colors LIKE IF(? = 'All', Colors, '%$color%')
        )
        OR (LOWER(ModelName) LIKE '%$keyword%' AND '$keyword' != '') 
        OR (LOWER(AdditionalInfo) LIKE '%$keyword%' AND '$keyword' != '')
        ORDER BY VIN_1";  
    }
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssssiisss", $type, $type, $manufacturer, $manufacturer, $model_year, $model_year, $vin, $vin, $color);
    $stmt->execute();
    $result = $stmt->get_result();
    include('lib/show_queries.php');
?>

<html>
<body>
    <div id="main_container">
        <div class="center_content">
            <div class="title">Vehicle List</div>  
            <table>
                <tr>
                    <td class=heading> </td>
                    <td class=heading>VIN</td>
                    <td class=heading>Vehicle Type</td>
                    <td class=heading>Model Year</td>
                    <td class=heading>Manufacturer</td>
                    <td class=heading>Model</td>
                    <td class=heading>Color</td>
                    <td class=heading>List Price</td>
                </tr>
            <?php
                $_SESSION['vin'] = ''; // reset to default setting
                $_SESSION['type'] = '';
                if (!empty($result) && (mysqli_num_rows($result) > 0)) {
                    while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
                   
                        print "<tr>";
                        // assign link to cell cell, then use $_GET parameters
                        if ($row[7] == 'Yes'){
                            print '<td>&#10004</td>'; # unicode for right mark 
                        } else{
                            print '<td></td>';
                        }
                        print "<td><a href='view_vehicle_detail.php?vin=" . $row[0] . "?type=". $row[1]. "'>". $row[0]. "</a></td>";
                        print "<td>$row[1]</td>";
                        print "<td>$row[2]</td>";
                        print "<td>$row[3]</td>";
                        print "<td>$row[4]</td>";
                        print "<td>$row[5]</td>"; 
                        print "<td>$row[6]</td>";
                        print "</tr>";	

                        }
                } else {
                        print "<tr>";
                        print "<td> Sorry, it looks like we don’t have that in stock! </td>";
                        print "</tr>"; 
                }	
            ?>
            </table>
            <a  href=<?php if (isset($_SESSION['username'])){echo "update_main_page.php";} else{echo "display_main_page.php";}?> style="float: right;"><button>MAIN PAGE</button></a>				
        </div>
    </div>
</body>
</html>
