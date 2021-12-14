<?php 
include('lib/common.php');

// constraint: page will be directed to the following if no username assigned
if (!isset($_SESSION['username'])) {
	header('Location: display_main_page.php');
	exit();
}
?>

<?php include("lib/header.php"); ?>
<head>
<title>Main Page</title>
</head>

<html>
<body>
    <div id="main_container">
        <div class="center_content">
            <div class="text_box">
                <div class='title'>Welcome to Jaunty Jalopies</div>
                <?php
                    $enteredUsername = $_SESSION['username'];
                    $_SESSION['manager'] = false;
                    $_SESSION['clerk'] = false;
                    $_SESSION['salesperson'] = false;
                    $_SESSION['servicewriter'] = false;
                    // echo 'default manager is: '. $_SESSION['manager'];
                    $query1 = "SELECT * FROM manager
                    WHERE UserName = '$enteredUsername'";
                    $query2 = "SELECT * FROM inventoryclerk
                    WHERE UserName = '$enteredUsername'";
                    $query3 = "SELECT * FROM salesperson
                    WHERE UserName = '$enteredUsername'";
                    $query4 = "SELECT * FROM servicewriter
                    WHERE UserName = '$enteredUsername'";
                    $result1 = mysqli_query($db, $query1);
                    $result2 = mysqli_query($db, $query2);
                    $result3 = mysqli_query($db, $query3);
                    $result4 = mysqli_query($db, $query4);
                    // include('lib/show_queries.php');
                    if (!empty($result1) && (mysqli_num_rows($result1) > 0)){
                        $_SESSION['manager'] = true;
                    }
                    if (!empty($result2) && (mysqli_num_rows($result2) > 0)){
                        $_SESSION['clerk'] = true;
                    }
                    if (!empty($result3) && (mysqli_num_rows($result3) > 0)){
                        $_SESSION['salesperson'] = true;
                    }
                    if (!empty($result4) && (mysqli_num_rows($result4) > 0)){
                        $_SESSION['servicewriter'] = true;
                    }
                ?>
                <br></br>
                <form method="post">
                    <table>
                        <tr>
                            <td><button type="submit" name="search_unsold">SEARCH UNSOLD</button></td>
                            <td><button type="submit" name="search_sold">SEARCH SOLD</button></td>
                        </tr>
                        <tr>
                            <td><button type="submit" name="search_all">SEARCH ALL</button></td>
                            <td><button type="submit" name="add_vehcile">ADD VEHICLE</button></td>
                        </tr>
                        <tr>
                            <td><button type="submit" name="open_repair">OPEN REPAIR</button></td>
                            <td><button type="submit" name="view_report">VIEW REPORT</button></td>
                        </tr>
                        </tr>
                        <td><button type="submit" name="log_out">LOG OUT</button></td>
                    </table>

                    <?php
                        if ($debug){
                            echo 'test2';
                        }
                    // after click search
                        if (isset($_POST['search_unsold'])) {
                            $_SESSION['search_type'] = "unsold";
                            // direct to search_vehicle.php, everyone after login has access to it
                            header(REFRESH_TIME . 'url=search_vehicle.php');
                        }
                        if (isset($_POST['search_sold'])) {
                            if (!empty($result1) && (mysqli_num_rows($result1) > 0)) {
                                print "<br>";
                                print "Have access as manager";
                                $_SESSION['search_type'] = "sold";
                                header(REFRESH_TIME . 'url=search_vehicle.php');
                            } else {
                                print "<br>";
                                print "You don't have the access as a manager!";
                            }
                        }
                        if (isset($_POST['search_all'])) {
                            if (!empty($result1) && (mysqli_num_rows($result1) > 0)) {
                                print "<br>";
                                print "Have access as manager";
                                $_SESSION['search_type'] = "all";
                                header(REFRESH_TIME . 'url=search_vehicle.php');
                            } else {
                                print "<br>";
                                print "You don't have the access as a manager!";
                            }
                        }
                        if (isset($_POST['view_report'])) {
                            if (!empty($result1) && (mysqli_num_rows($result1) > 0)) {
                                print "<br>";
                                print "Have access as owner or manager";
                                header(REFRESH_TIME . 'url=view_report.php');
                            } else {
                                print "<br>";
                                print "You don't have the access as an owner or manager!";
                            }
                        }
                        if (isset($_POST['add_vehcile'])) {
                            if (!empty($result2) && (mysqli_num_rows($result2) > 0)) {
                                print "<br>";
                                print "Have access as clerk";
                                header(REFRESH_TIME . 'url=add_vehicle.php');
                            } else {
                                print "<br>";
                                print "You don't have the access as a clerk!";
                            }
                        }
                        if (isset($_POST['open_repair'])) {
                            if (!empty($result4) && (mysqli_num_rows($result4) > 0)) {
                                print "<br>";
                                print "Have access as servicewriter";
                                header(REFRESH_TIME . 'url=open_repair.php');
                            } else {
                                print "<br>";
                                print "You don't have the access as a servicewriter!";
                            }
                        }
                        if (isset($_POST['log_out'])) {
                            if (!empty($result4) && (mysqli_num_rows($result4) > 0)) {
                                print "<br>";
                                print "GoodBye";
                                header(REFRESH_TIME . 'url=logout.php');
                             } else {
                                print "<br>";
                                print "GoodBye";
                             }
                        }
                    ?>
                </form>

            </div>
            <?php include("lib/error.php"); ?>
            <div class="clear"></div>
        </div>
    </div>
</body>
</html>

<!-- #TODO Both in nav bar?
1: for any page after update_main_page, add 'main page' button and redirect to update_main_page
2: for any page after login, add 'log out' button and redirect to display_main_page -->
