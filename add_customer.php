<?php include('lib/common.php');
include("lib/header.php");
if (!isset($_SESSION['username'])) {
	header('Location: search_customer.php');
    exit();
}

?>


<head>
<title>Add Customer Page</title>
</head>

<?php
if ($_SERVER['REQUEST_METHOD']=='POST'){
    $customer_identifier_type=mysqli_real_escape_string($db, $_POST['customer_identifier_type']);
    $_SESSION['customerID']='';
    $_SESSION['new_customer_identifier_type']=$customer_identifier_type;
}
?>

<html>
<body>
    <div id="main_container">
        <div class="center_content">
            <div class="text_box">
                <form action="add_customer.php" method="post" enctype="multipart/form-data">
                    <div class="title">Add Customer</div>
                    <div class="add_customer">
                        <label class="customer_type_label">Select Customer Identity Type:</label>
                        <select name="customer_identifier_type" class="form-control">
                             <option value=""disabled selected>Choose option</option>
                             <option value="TaxID">TaxID</option>
                             <option value="DriverLicense">DriverLicense</option>
                        </select>  
                        <input type="submit" name="submit" vlaue="Choose options">
                    </div>
                </form>
                <?php
                if ($customer_identifier_type=='TaxID'){
                    print "<form action='view_added_customer.php' method='post' class='search'>
                    <label class='customer_label'>Tax ID: </label>
                    <input type='text' name='tax_id' value='' class='customer_input'/><br>
                    <label class='customer_label'>First Name: </label>
                    <input type='text' name='first_name' value='' class='customer_input'/><br>
                    <label class='customer_label'>Last Name: </label>
                    <input type='text' name='last_name' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Business Name: </label>
                    <input type='text' name='business_name' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Primary Contact Title: </label>
                    <input type='text' name='primary_contact_title' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Phone Number: </label>
                    <input type='text' name='phone_number' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Street: </label>
                    <input type='text' name='street' value='' class='customer_input'/> <br>
                    <label class='customer_label'>City: </label>
                    <input type='text' name='city' value='' class='customer_input'/> <br>
                    <label class='customer_label'>State: </label>
                    <input type='text' name='state' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Postcode: </label>
                    <input type='text' name='postcode' value='' class='customer_input'/> <br>
                    <label class='customer_label'>Email: </label>
                    <input type='text' name='email' value='' class='customer_input'/> <br>
                    <a  href='view_added_customer.php' style='float: right;'><button>ADD</button></a>
                    </form><br>";
                    


                    }
               
                    
                    if ($customer_identifier_type=='DriverLicense'){
                        print "<form action='view_added_customer.php' method='post' class='search'>
                        <label class='customer_label'>Driver's License: </label>
                        <input type='text' name='driver_license' value='' class='customer_input'/> <br>
                        <label class='customer_label'>First Name: </label>
                        <input type='text' name='first_name' value='' class='customer_input'/> <br>
                        <label class='customer_label'>Last Name: </label>
                        <input type='text' name='last_name' value='' class='customer_input'/> <br>
                        <label class='customer_label'>Phone Number: </label>
                        <input type='text' name='phone_number' value='' class='customer_input'/> <br>
                        <label class='customer_label'>Street: </label>
                        <input type='text' name='street' value='' class='customer_input'/> <br>
                        <label class='customer_label'>City: </label>
                        <input type='text' name='city' value='' class='customer_input'/> <br>
                        <label class='customer_label'>State: </label>
                        <input type='text' name='state' value='' class='customer_input'/> <br>
                        <label class='customer_label'>Postcode: </label>
                        <input type='text' name='postcode' value='' class='customer_input'/> <br>
                        <label class='customer_label'>Email: </label>
                        <input type='text' name='email' value='' class='customer_input'/> <br>
                        <a  href='view_added_customer.php' style='float: right;'><button>ADD</button></a>
                        </form><br>";
                        }
                 
                



                ?>


                    

                    
  



                
            </div>
        </div>
        <?php include("lib/error.php"); ?>
        <div class="clear"></div>
    </div>
</body>
</html>
