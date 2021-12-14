<?php include('lib/common.php');
include("lib/header.php");
if (!isset($_SESSION['username'])) {
	header('Location: view_vehicle_detail.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD']=='POST'){
     $customer_identifier=strval(mysqli_real_escape_string($db, $_POST['customer_identifier']));
     $customer_identifier_type=mysqli_real_escape_string($db, $_POST['customer_identifier_type']);
     $result_business='';
     $result_individual='';
     $_SESSION['customerID']='';
     $query='';
       if ($customer_identifier_type=='TaxID') {
         $query="SELECT c.CustomerID, b.TaxID, b.FirstName, b.LastName, b.Businessname, b.PrimaryContactTitle, c.PhoneNumber, c.Street, c.City, c.State, c.Postalcode, c.Email
         FROM Business AS b INNER JOIN Customer AS c
         ON b.CustomerID = c.CustomerID
         WHERE b.TaxID = '{$customer_identifier}'";

         $result_business = mysqli_query($db, $query);
         include('lib/show_queries.php');

       }
       elseif ($customer_identifier_type=="DriverLicense") {
         $query="SELECT c.CustomerID, i.DriverLicenceNum, i.FirstName, i.LastName,
         c.PhoneNumber, c.Street, c.City, c.State, c.PostalCode, c.Email
         FROM Individual AS i INNER JOIN Customer AS c
         ON i.CustomerID = c.CustomerID
         WHERE i.DriverLicenceNum = '{$customer_identifier}'";

         $result_individual = mysqli_query($db, $query);
         include('lib/show_queries.php');
      }
}


?>

<head>
<title>Search Customer Page</title>
</head>

<html>
<body>
    <div id="main_container">
        <div class="center_content">
            <div class="text_box">
                 <form action="search_customer.php" method="post" class="mb-3">
                 <div class="title">Search Customer</div>
                 <div class="search_customer">
                        <label class="sell_vehicle_label">VIN:</label>
                        <?php echo $_SESSION["vin"];?>
                 </div>
                 <div class='search_customer'>
                     <label class="customer_identifier_type">Search Customer By:</label>
                     <br>
                     <select name="customer_identifier_type" class="form-control">
                         <option value=""disabled selected>Choose option</option>
                         <option value="TaxID">TaxID</option>
                         <option value="DriverLicense">DriverLicense</option>
                     </select>
                     <div>
                     <input type="text" name="customer_identifier" value="" class="customer_identifier"/>
                     </div>
                 </div>
                 <a  href="search_customer.php" style="float: right;"><button type='submit' name="search">SEARCH</button></a>	
                 </form>
                 <a  href="update_main_page.php" style="float: right;"><button>MAIN PAGE</button></a>
             </div>




            <div class="center_left">
                 <div class='customer_section'>
                 <div class='subtitle'>Search Result:</div>
                 <table>

                 <?php
                 if (empty($customer_identifier_type) or empty($customer_identifier)) {
                    print "<tr>";
                    print "<td> Please fill all the blanks!</td>";
                    print "</tr>";

                 }
                 else {
                   if (!empty($result_business) && (mysqli_num_rows($result_business) > 0)) {
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
                     while ($row = mysqli_fetch_array($result_business, MYSQLI_NUM)) {
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
                        print "<td>$row[11]</td>";
                        // print '<td><a class="select" href="edit_sales_record.php">select</a></td>';
                        print "<td><a href='edit_sales_record.php?customerID=" . $row[0] . "'>SELECT</a></td>";
                        print "</tr>";
                        print "</table>";
                    }
                   }


                  elseif (!empty($result_individual) && (mysqli_num_rows($result_individual) > 0)){
                        print "<table>";
                        print "<tr>";
                        print "<td> CustomerID </td>";
                        print "<td> DriverLicenseNum </td>";
                        print "<td> FirstName </td>";
                        print "<td> LastName </td>";
                        print "<td> PhoneNumber </td>";
                        print "<td> Street </td>";
                        print "<td> City </td>";
                        print "<td> State </td>";
                        print "<td> Postcode </td>";
                        print "<td> Email </td>";
                        print "</tr>";
                        while ($row2 = mysqli_fetch_array($result_individual, MYSQLI_NUM)) {
                            $_SESSION['customerID']=$row2[0];
                            print "<tr>";
                            print "<td>$row2[0]</td>";
                            print "<td>$row2[1]</td>";
                            print "<td>$row2[2]</td>";
                            print "<td>$row2[3]</td>";
                            print "<td>$row2[4]</td>";
                            print "<td>$row2[5]</td>";
                            print "<td>$row2[6]</td>";
                            print "<td>$row2[7]</td>";
                            print "<td>$row2[8]</td>";
                            print "<td>$row2[9]</td>";
                            print "<td>$row2[10]</td>";
                            print "<td><a href='edit_sales_record.php?customerID=" . $row2[0] . "'>SELECT</a></td>";
                            print "</tr>";
                            print "</table>";
                        }
                    }
                  else {
                        print "<tr>";
                        print "<td> Sorry, it looks like we don’t have that customer on file!</td>";
                        print "<td><a  href='add_customer.php' style='float: right;'><button>ADD CUSTOMER</button></a></td>";
                        print "</tr>";
                    }
                }
                 ?>
                 </table>
                 </div>
             </div>

         </div>
        <?php include("lib/error.php"); ?>
        <div class="clear"></div>
    </div>
</body>
</html>
