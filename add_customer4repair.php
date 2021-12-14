<?php include('lib/common.php');
include("lib/header.php");
if (!isset($_SESSION['username'])) {
	header('Location: search_customer.php');
    exit();
}
?>

<head>
<title>Add Repair Record (Add Customer)</title>
</head>

<?php
$customer_identifier_type = $_SESSION['customer_type'];
$_SESSION['added_flag'] = FALSE;
if ($_SERVER['REQUEST_METHOD']=='POST'){
    //taxid
    if ($customer_identifier_type=='TaxID'){
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
                $_SESSION['added_flag'] = TRUE;
            }
            else {
                print "Added Business Customer Failed...";
                print "<br>";
            } 
        }
        else {
            print "Added Customer Failed...";
        }
        //get the new customer id
        $query_id = "SELECT CustomerID FROM business WHERE TaxID = '$tax_id'";
        $result = mysqli_query($db,$query_id);
        $_SESSION['customerID'] = mysqli_fetch_array($result)[0];
    }

    if ($customer_identifier_type=='DriverLicense'){
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
                $_SESSION['added_flag'] = TRUE;

            }
            else {
                print "Added Individual Customer Failed...";
                print "<br>";
            } 
        }
        else {
            print "Added Customer Failed...";
        }   
        $query_id = "SELECT CustomerID FROM individual WHERE DriverLicenceNum = '$driver_license'";
        $result = mysqli_query($db,$query_id);
        $_SESSION['customerID'] = mysqli_fetch_array($result)[0];
    }
}
?>

<html>
<body>
    <div id="main_container">
        <div class="center_content">
            <div class="text_box">
            <div class="title">Add Repair Record: 1.5 Add Customer</div>
                <?php
                if ($customer_identifier_type=='TaxID'){
                    print "<form action='add_customer4repair.php' method='post' class='search'>
                    <label class='customer_label'>Tax ID</label>
                    <input type='text' name='tax_id' value='' class='customer_input'/> <br>
                    <label class='customer_label'>First Name</label>
                    <input type='text' name='first_name' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Last Name</label>
                    <input type='text' name='last_name' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Business Name</label>
                    <input type='text' name='business_name' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Primary Contact Title</label>
                    <input type='text' name='primary_contact_title' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Phone Number</label>
                    <input type='text' name='phone_number' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Street</label>
                    <input type='text' name='street' value='' class='customer_input'/> <br>
                    <label class='customer_label'>City</label>
                    <input type='text' name='city' value='' class='customer_input'/> <br>
                    <label class='customer_label'>State</label>
                    <input type='text' name='state' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Postcode</label>
                    <input type='text' name='postcode' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Email</label>
                    <input type='text' name='email' value='' class='customer_input'/> <br>
                    <input type= 'submit' value='ADD'>
                    </form><br>";
                }
                    
                if ($customer_identifier_type=='DriverLicense'){
                    print "<form action='add_customer4repair.php' method='post' class='search'>
                    <label class='customer_label'>Driver's License</label>
                    <input type='text' name='driver_license' value='' class='customer_input'/> <br>
                    <label class='customer_label'>First Name</label>
                    <input type='text' name='first_name' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Last Name</label>
                    <input type='text' name='last_name' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Phone Number</label>
                    <input type='text' name='phone_number' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Street</label>
                    <input type='text' name='street' value='' class='customer_input'/> <br>
                    <label class='customer_label'>City</label>
                    <input type='text' name='city' value='' class='customer_input'/> <br>
                    <label class='customer_label'>State</label>
                    <input type='text' name='state' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Postcode</label>
                    <input type='text' name='postcode' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Email</label>
                    <input type='text' name='email' value='' class='customer_input'/> <br>
                    <input type= 'submit' value='ADD'>
                    </form><br>";
                }
                if ($_SESSION['added_flag']==TRUE){
                    print "<a  href='add_new_repair.php' style='float: right;'><button type='submit' name='continue'>Continue</button></a>";
                }
                ?>
            </div>
        </div>
        <?php include("lib/error.php"); ?>
        <div class="clear"></div>
    </div>
</body>
</html>