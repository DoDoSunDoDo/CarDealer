<?php include('lib/common.php');
include("lib/header.php");
if (!isset($_SESSION['username'])) {
	header('Location: view_vehicle_detail.php');
    exit();
}
if (($_SESSION['addable']==FALSE)||($_SESSION['eligible']==0)){
    header('Location: view_vehicle_repair.php?vin='.$_SESSION['vin'].'&search=');
}
if ($_SERVER['REQUEST_METHOD']=='POST'){
     $customer_identifier=strval(mysqli_real_escape_string($db, $_POST['customer_identifier']));
     $customer_identifier_type=mysqli_real_escape_string($db, $_POST['customer_identifier_type']);
     $_SESSION['customer_type'] = $customer_identifier_type;
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
<title>Search Customer for Repair Record</title>
</head>

<html>
<body>
    <div id="main_container">
        <div class="center_content">
            <div class="text_box">
                 <form action="search_customer4repair.php" method="post" class="mb-3">
                 <div class="title">Add Repair Record: 1 Search Customer</div>
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
                         <label class="customer_identifier_type">Customer Identifier</label>
                         <input type="text" name="customer_identifier" value="" class="customer_identifier"/>
                         <a  href="search_customer4repair.php" style="float: right;"><button type='submit' name="search">SEARCH</button></a>
                     </div>
                 </div>
                 </form>
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
                        print "</tr>";	
                        print "</table>";
                        print "<tr>";
                        print "<td><a  href='add_new_repair.php' style='float: right;'><button>CONTINUE</button></a></td>";
                        print "</tr>"; 
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
                            print "</tr>";
                            print "</table>";
                            print "<tr>";
                            print "<td><a  href='add_new_repair.php' style='float: right;'><button>CONTINUE</button></a></td>";
                            print "</tr>"; 	
                        }
                    }
                  else {
                        print "<tr>";
                        print "<td> Sorry, it looks like we don’t have that customer on file!</td>";
                        print "<td><a  href='add_customer4repair.php' style='float: right;'><button>ADD CUSTOMER</button></a></td>";
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