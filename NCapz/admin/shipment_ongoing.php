<?php
    //START A SESSION
    session_start();

    include('../server.php');

    $username = $permission = $staffID = $profilePicture = "";

    //CHECK IF THE USER IS LOGGED IN
    if($_SESSION["admin"] !== true){
        header("location: login.php");
        exit;
    }

    $permission = $_SESSION["permission"];
    $uid = $_SESSION["uid"];

    //GET USER INFORMATION
    $sql_userInformation = "SELECT * FROM user WHERE uid='$uid'";
    $res_userInformation = mysqli_query($conn, $sql_userInformation);
    $row_userInformation = mysqli_fetch_array($res_userInformation);

    $username = $row_userInformation['username'];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <script src="../js/javascript.js"></script>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="body">
    <header>
        <div class="container-wrapper">
            <div id="container-wrapper-item1">
                <span id="admin-panel">NCapz | Admin Panel</span>
            </div>

            <div id="container-wrapper-item2">
                <span id="wrapper-username"><?php echo $username; ?></span>
                <img id="wrapper-staff-pic" src="../resource/staff_pic/default.png"></img>
            </div>
        </div>
    </header>

    <div style="height: 60px; width: 100%;"></div>

    <section id="main-container">
        <div class="nav-menu">
            <br><br><br>
            <a href="index.php" style="text-decoration: none;">
                <div  class="nav-menu-items">
                    <i class='bx bxs-dashboard icon'></i>|  
                    <span>Dashboard</span>
                </div>
            </a>

            <hr>

            <a href="shipment_information.php" style="text-decoration: none;">
                <div style="background-color: rgb(218, 218, 218); color: #282730;" class="nav-menu-items">
                    <i class='bx bxs-package icon'></i>|
                    <span>Shipment</span>
                </div>
            </a>

            <hr>

            <a href="product_information.php" style="text-decoration: none;">
                <div class="nav-menu-items">
                    <i class='bx bxs-store-alt icon'></i>|
                    <span>Products</span>
                </div>
            </a>
            
            <hr>

            <a href="category_information.php" style="text-decoration: none;">
                <div  class="nav-menu-items">
                    <i class='bx bxs-component icon'></i>|
                    <span>Categories</span>
                </div>
            </a>

            <hr>

            <a href="logout.php">
                <div id="logout-menu" class="nav-menu-items">
                    <hr>
                    <i class='bx bxs-log-out icon'></i>|
                    <span>Logout</span>
                    <hr>
                </div>
            </a>
        </div>
        

        <div class="nav-bar-breaker">
        </div>

        <div class="main-container">
            <div class="container-nav-bar">
                <a style="text-decoration: none;" href="shipment_information.php">
                    <div class="container-nav-bar-items">
                        <span>Shipment Information</span>
                    </div>
                </a>

                <div class="container-nav-bar-items">
                    <span>|</span>
                </div>

                <a style="text-decoration: none;" href="shipment_ongoing.php">
                    <div style="border-bottom: 2px solid white;" class="container-nav-bar-items">
                        <span>Ongoing Shipment</span>
                    </div>
                </a>

                <div class="container-nav-bar-items">
                    <span>|</span>
                </div>

                <a style="text-decoration: none;" href="shipment_completed.php">
                    <div class="container-nav-bar-items">
                        <span>Completed Shipment</span>
                    </div>
                </a>
            </div>

            <div class="container-items">
                <div style="width: 95%; margin: auto;">
                    <br>
                    <table id="styling-table1">
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Tracking Number</th>
                            <th>Action</th>
                        </tr>
                            <?php
                                $sqll = "Select count(*) from cart WHERE status='Ongoing Shipment'";
                                $retnum = mysqli_query($conn,$sqll);
                                $count = mysqli_fetch_array($retnum, MYSQLI_NUM);
                                $rec_count = $count['0'];
                                $rec_limit = 6;
                                $last_page = ceil($rec_count/$rec_limit);
                                
                                if(!empty($_REQUEST["page"])){
                                $page = $_REQUEST['page'];
                                $offset = ($page - 1) * $rec_limit;
                                }else{
                                    $page= 1;
                                    $offset = 0;
                                }
                                $postp = $page + 1;
                                $prevp = $page - 1;

                                $sql2 = "Select * from cart WHERE status='Ongoing Shipment' limit $offset, $rec_limit";
                                $result = mysqli_query($conn,$sql2);
                                

                                if(mysqli_num_rows($result) > 0){
                                    while($row = mysqli_fetch_assoc($result)){
                                        $productID = $row['product_id'];

                                        //THIS IS FOR THE PRODUCT DETAILS
                                        $sql_productInformation = "SELECT * FROM product WHERE product_id='$productID'";
                                        $res_productInformation = mysqli_query($conn, $sql_productInformation);
                                        $row_productInformation = mysqli_fetch_array($res_productInformation);

                                        echo "<tr>";
                                            echo "<td>".$row['cart_id']."</td>";
                                            echo "<td style='text-align: center;'><img style='margin: auto; width: 35px; height: 35px;' src='../resource/product_pic/".$row_productInformation['picture']."'></img></td>";
                                            echo "<td>".$row_productInformation['name']."</td>";
                                            echo "<td>".$row['address']."</td>";
                                            echo "<td>".$row['tracking_num']."</td>";
                                            echo "<td><button class='action-btn' onclick=\"linkTrack('".$row['tracking_num']."')\">TRACK</button>
                                            <a style='text-decoration: none;' href='order_received_admin.php?cartID=".$row['cart_id']."'><div class='action-btn'><span>Received</span></div></a></td>";
                                        echo "</tr>";
                                    }
                                }else{
                                    echo "<tr>";
                                        echo "<td style='text-align: center;' colspan='7'>Empty!</td>";
                                    echo "</tr>";
                                }

                                echo "</table>";
                                
                                echo "<div class='all-course-pagination-btn'>";
                                    //For the pagination...
                                    if ($page > 1){
                                    echo "<div class='all-course-pagination-previous-btn'>";
                                        echo "<a style='color: white; background-color: #59761E; padding: 10px; margin: auto; border: black 2px solid;' href = '".$_SERVER['PHP_SELF']."?page=$prevp'>Previous Page</a>";
                                    echo "</div>";
                                    }
                                    if ($prevp < $last_page-1){ 
                                    echo "<div class='all-course-pagination-next-btn'>";
                                        echo "<a style='color: white; background-color: #59761E; padding: 10px; margin: auto; border: black 2px solid;' href = '".$_SERVER['PHP_SELF']."?page=$postp'>Next Page</a>";
                                    echo "</div>";
                                    }
                                echo "</div>";
                            ?>
                </div>
            </div>
        </div>
    </section>

    <!-- <footer>
        <span><i class='bx bx-copyright icon'></i> Copyright 2021</spam>
    </footer> -->
</body>

<!-- THIS IS POST TRACKING EMBED -->
<script src="//www.tracking.my/track-button.js"></script>
<script>
  function linkTrack(num) {
    TrackButton.track({
      tracking_no: num
    });
  }
</script>

</html>