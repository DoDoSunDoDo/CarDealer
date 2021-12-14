<?php include('lib/common.php');
include("lib/header.php");
if (!isset($_SESSION['username'])) {
	header('Location: add_customer.php');
    exit();
}
?>

<head>
<title>View Added Customer Detail Page</title>
</head>

<?php
    if ($_SERVER['REQUEST_METHOD']=='POST'){
        if ($_SESSION['new_customer_identifier_type']=='TaxID'){
            $tax_id=strval(mysqli_real_escape_string($db, $_POST['tax_id']));
            $first_name=strval(mysqli_real_escape_string($db, $_POST['first_name']));
            $last_name=strval(mysqli_real_escape_string($db, $_POST['last_name']));
            $business_name=strval(mysqli_real_escape_string($db, $_POST['business_name']));
            $primary_contact_title=strval(mysqli_real_escape_string($db, $_POST['primary_contact_title']));
            $phone_number=strval(mysqli_real_escape_string($db, $_POST['phone_number']));
            $street=strval(mysqli_real_escape_string($db, $_POST['street']));
            $city=strval(mysqli_real_escape_string($db, $_POST['city']));
            $state=strval(mysqli_real_escape_string($db, $_POST['state']));
            $postcode=strval(mysqli_real_escape_string($db, $_POST['postcode']));
            $email=strval(mysqli_real_escape_string($db, $_POST['email']));
            $query_customer1 = "INSERT INTO Customer (City, Email, PhoneNumber, PostalCode, `State`, Street) 
                      VALUES ('$city', '$email', '$phone_number', '$postcode', '$state', '$street')";
            $query_customer2 ="SET @CustomerID = LAST_INSERT_ID()";
            $query_business = "INSERT INTO Business (CustomerID, TaxID, BusinessName, PrimaryContactTitle, FirstName, LastName)
                      VALUES (@CustomerID, '$tax_id', '$business_name', '$primary_contact_title', '$first_name', '$last_name')";

            if (mysqli_query($db, $query_customer1)) {
                print "Added Customer Successfully!";
                if (mysqli_query($db, $query_customer2)&&mysqli_query($db, $query_business)) {
                    print "Added Business Customer Successfully!";
                    print "<br>";
                    
                }
                else {
                    print "Added Business Customer Failed...";
                    print "<br>";
                } 
            }
            else {
                print "Added Customer Failed...";
            }


        }
        
        if ($_SESSION['new_customer_identifier_type']=='DriverLicense'){
            $driver_license=strval(mysqli_real_escape_string($db, $_POST['driver_license']));
            $first_name=strval(mysqli_real_escape_string($db, $_POST['first_name']));
            $last_name=strval(mysqli_real_escape_string($db, $_POST['last_name']));
            $phone_number=strval(mysqli_real_escape_string($db, $_POST['phone_number']));
            $street=strval(mysqli_real_escape_string($db, $_POST['street']));
            $city=strval(mysqli_real_escape_string($db, $_POST['city']));
            $state=strval(mysqli_real_escape_string($db, $_POST['state']));
            $postcode=strval(mysqli_real_escape_string($db, $_POST['postcode']));
            $email=strval(mysqli_real_escape_string($db, $_POST['email']));
            $query_customer1 = "INSERT INTO Customer (City, Email, PhoneNumber, PostalCode, `State`, Street) 
            VALUES ('$city', '$email', '$phone_number', '$postcode', '$state', '$street')";
            $query_customer2 ="SET @CustomerID = LAST_INSERT_ID()";
            $query_individual = "INSERT INTO Individual (CustomerID, DriverLicenceNum, FirstName, LastName)
            VALUES (@CustomerID, '$driver_license', '$first_name', '$last_name')";

            if (mysqli_query($db, $query_customer1)) {
                print "Added Customer Successfully!";
                if (mysqli_query($db, $query_customer2)&&mysqli_query($db, $query_individual)) {
                    print "Added Individual Customer Successfully!";
                    print "<br>";
                }
                else {
                    print "Added Individual Customer Failed...";
                    print "<br>";
                } 
            }
            else {
                print "Added Customer Failed...";
            }   

        }
    }
                    
?>

<html>
<body>
    <div id="main_container">
        <div class="center_content">
            <div class="title">Added Customer Detail</div>  
            <?php
            if ($_SESSION['new_customer_identifier_type'] == 'TaxID'){
                $new_tax_id=strval(mysqli_real_escape_string($db, $_POST['tax_id']));
                $query_business_detail = "SELECT b.*, c.PhoneNumber, c.Street, c.City, c.State, c.PostalCode, c.Email
                FROM Customer c
                JOIN Business b
                ON c.CustomerID=b.CustomerID
                where b.TaxID='$new_tax_id'";
                $result_business_detail = mysqli_query($db, $query_business_detail);
                print "<table>";
                print "<tr>";
                print "<td> CustomerID </td>";
                print "<td> TextID </td>";
                print "<td> FirstName </td>";
                print "<td> LastName </td>";
                print "<td> BusinessName </td>";
                print "<td> PrimaryContactTitle </td>";
                print "<td> PhoneNumber </td>";
                print "<td> Street </td>";
                print "<td> City </td>";
                print "<td> State </td>";
                print "<td> Postcode </td>";
                print "<td> Email </td>";
                print "</tr>";
                while ($row = mysqli_fetch_array($result_business_detail, MYSQLI_NUM)) {
                    $_SESSION['customerID']=$row[0];
                    print "<tr>";
                    print "<td>$row[0]</td>";
                    print "<td>$row[1]</td>";
                    print "<td>$row[2]</td>";
                    print "<td>$row[3]</td>";
                    print "<td>$row[4]</td>";
                    print "<td>$row[5]</td>"; 
                    print "<td>$row[6]</td>";
                    print "<td>$row[7]</td>";
                    print "<td>$row[8]</td>";
                    print "<td>$row[9]</td>";
                    print "<td>$row[10]</td>";
                    print "<td>$row[11]</td>";
                    // print '<td><a class="select" href="edit_sales_record.php">select</a></td>';
                    print "<td><a href='edit_sales_record.php?customerID=" . $row[0] . "'>SELECT</a></td>";
                    print "</tr>";
                    print "</table>";	
                }


            }
            if ($_SESSION['new_customer_identifier_type'] == 'DiverLicense'){
                $new_driver_license=strval(mysqli_real_escape_string($db, $_POST['driver_license']));
                $query_individual_detail = "SELECT b.*, c.PhoneNumber, c.Street, c.City, c.State, c.PostalCode, c.Email
                FROM Customer c
                JOIN Individual i
                ON c.CustomerID=i.CustomerID
                where b.TaxID='$new_driver_license'";
                $result_individual_detail = mysqli_query($db, $query_individual_detail);
                print "<table>";
                print "<tr>";
                print "<td> CustomerID </td>";
                print "<td> Driver's License </td>";
                print "<td> FirstName </td>";
                print "<td> LastName </td>";
                print "<td> PhoneNumber </td>";
                print "<td> Street </td>";
                print "<td> City </td>";
                print "<td> State </td>";
                print "<td> Postcode </td>";
                print "<td> Email </td>";
                print "</tr>";
                while ($row = mysqli_fetch_array($result_individual_detail, MYSQLI_NUM)) {
                    $_SESSION['customerID']=$row[0];
                    print "<tr>";
                    print "<td>$row[0]</td>";
                    print "<td>$row[1]</td>";
                    print "<td>$row[2]</td>";
                    print "<td>$row[3]</td>";
                    print "<td>$row[4]</td>";
                    print "<td>$row[5]</td>";
                    print "<td>$row[6]</td>";
                    print "<td>$row[7]</td>";
                    print "<td>$row[8]</td>";
                    print "<td>$row[9]</td>";
                    // print '<td><a class="select" href="edit_sales_record.php">select</a></td>';
                    print "<td><a href='edit_sales_record.php?customerID=" . $row[0] . "'>SELECT</a></td>";
                    print "</tr>";
                    print "</table>";	
                }

            }
                

                
            ?>
        </div>
    </div>
</body>
<html>