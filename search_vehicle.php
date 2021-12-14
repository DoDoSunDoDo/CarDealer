 
<?php include('lib/common.php');
include("lib/header.php");
?>

<head>
<title>Search Vehicle Page</title>
</head>

<html>
<body>
    <div id="main_container">
        <div class="center_content">
            <div class="text_box">
                <!-- HTML form action: to specify where the data is sent to be processed
                GET method: for non-sensitive data and allows bookmarking pages
                POST method: for sensitive data as it is considered more secure -->
                <form action="view_vehicle_list.php" method="post" enctype="multipart/form-data">
                    <div class="title">Jaunty Jalopies Search</div>
                    <div class="login_form_row">
                        <label class="login_label">Vehicle Type:</label>
                        <select name="type_for_search" class="select"> 
                            <option value="All">All</option>  
                            <?php
                                $query = "SELECT DISTINCT VehicleType FROM vehicle_detail ORDER BY VehicleType";
                                $result=mysqli_query($db, $query);  
                                include('lib/show_queries.php');
                                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){  
                                ?>  
                                    <option value="<?php print $row['VehicleType']; ?>"><?php print $row['VehicleType'];?></option>  
                                <?php  
                                }  
                                ?>  
                        </select>  
                    </div>
                    <div class="login_form_row">
                        <label class="login_label">Manufacturer:</label>
                        <select name="manufacturer" class="select">
                            <option value="All">All</option>   
                            <?php
                                $query = "SELECT DISTINCT ManufacturerName FROM manufacturer";
                                $result=mysqli_query($db, $query); 
                                include('lib/show_queries.php');
                                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){  
                                ?>  
                                    <option value="<?php print $row['ManufacturerName']; ?>"><?php print $row['ManufacturerName'];?></option>  
                                <?php  
                                }  
                                ?>  
                        </select>  
                    </div>
                    <div class="login_form_row">
                        <label class="login_label">Model Year:</label>
                        <select name="model_year" class="select"> 
                            <option value="All">All</option>  
                            <?php
                                $query = "SELECT DISTINCT ModelYear FROM vehicle ORDER BY ModelYear";
                                $result=mysqli_query($db, $query);
                                include('lib/show_queries.php'); 
                                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){  
                                ?>  
                                    <option value="<?php print $row['ModelYear']; ?>"><?php print $row['ModelYear'];?></option>  
                                <?php  
                                }  
                                ?>  
                        </select>  
                    </div> 
                    <div class="login_form_row">
                        <label class="login_label">Color:</label>
                        <select name="color" class="select"> 
                            <option value="All">All</option>  
                            <?php
                                $query = "SELECT DISTINCT ColorDescription FROM Color";
                                $result=mysqli_query($db, $query);  
                                include('lib/show_queries.php');
                                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){  
                                ?>  
                                    <option value="<?php print $row['ColorDescription']; ?>"><?php print $row['ColorDescription'];?></option>  
                                <?php  
                                }  
                                ?>  
                        </select> 
                    </div> 
                    <div class="login_form_row">
                        <label class="login_label">Minimum List Price:</label>
                        <input type="number" name="min_list_price" value=0 class="login_input"/> 
                    </div> 
                    <div class="login_form_row">
                        <label class="login_label">Maximum List Price:</label>
                        <input type="number" name="max_list_price" value=99999999 class="login_input"/> 
                    </div>   
                    <div class="login_form_row">
                        <label class="login_label">Keyword:</label>
                        <input type="text" name="keyword" value="Enter model year, manufacturer, model, desc here. E.g. 2017 or Audi etc." class="login_input"/>
                        <!-- # TODO: set default text: e.g. enter model year: 2017; enter manufacturer: Audi -->
                    </div> 

                    <!-- depends on whether login or non-login he/she may allow to use VIN   -->
                    <!-- TODO: fix white layout in text box -->
                    <?php 
                        if ($_SESSION['username'] != null) {
                        ?>
                            <div class="login_form_row">
                                <label class="login_label">VIN:</label>
                                <input type="text" name="vin_for_search" value="All" class="login_input"/> 
                            </div> 
                        <?php
                        }
                        ?>

                    <div>
                        <a  href=<?php if (isset($_SESSION['username'])){echo "update_main_page.php";} else{echo "display_main_page.php";}?> style="float: right;"><button>MAIN PAGE</button></a>	
                        <a  href="view_vehicle_list.php" style="float: right;"><button type='submit' name="search">SEARCH</button></a>	
                    </div>  
                </form>
            </div>
        </div>
        <?php include("lib/error.php"); ?>
        <div class="clear"></div>
    </div>
</body>
</html>
