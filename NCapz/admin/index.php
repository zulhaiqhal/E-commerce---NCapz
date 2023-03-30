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
    $username = $_SESSION["username"];
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
                <span id="wrapper-username">Admin</span>
                <img id="wrapper-staff-pic" src="../resource/staff_pic/default.png"></img>
            </div>
        </div>
    </header>

    <div style="height: 60px; width: 100%;"></div>

    <section id="main-container">
        <div class="nav-menu">
            <br><br><br>
            <a href="index.php" style="text-decoration: none;">
                <div style="background-color: rgb(218, 218, 218); color: #282730;" class="nav-menu-items">
                    <i class='bx bxs-dashboard icon'></i>|  
                    <span>Dashboard</span>
                </div>
            </a>

            <hr>

            <a href="shipment_information.php" style="text-decoration: none;">
                <div class="nav-menu-items">
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
                <div class="nav-menu-items">
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
                <div style="border-bottom   : 2px solid white;" class="container-nav-bar-items">
                    <span>Dashboard</span>
                </div>
            </div>

            <div class="container-items">
                <div style="width:80%; margin: auto;">
                    <br>
                    <div class="dashboard-flex-container">
                        <div class="dashboard-flex-items">
                            <div class="dashboard-flex-header">
                                <span>Unpaid</span>
                            </div>
                            <div class="dashboard-flex-info">
                                <div>
                                    <?php
                                        $counter_unpaid = 0;

                                        $sql_selectUnpaid = "SELECT * FROM cart WHERE status='unpaid'";
                                        $res_selectUnpaid = mysqli_query($conn, $sql_selectUnpaid);

                                        if(mysqli_num_rows($res_selectUnpaid) > 0){
                                            while($row = mysqli_fetch_array($res_selectUnpaid)){
                                                $counter_unpaid = $counter_unpaid + 1;
                                            }
                                        }

                                        echo "<span>".$counter_unpaid."</span>";
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="dashboard-flex-items">
                            <div class="dashboard-flex-header">
                                <span>Paid</span>
                            </div>
                            <div class="dashboard-flex-info">
                                <div>
                                    <?php
                                        $counter_unpaid = 0;

                                        $sql_selectUnpaid = "SELECT * FROM cart WHERE status='paid'";
                                        $res_selectUnpaid = mysqli_query($conn, $sql_selectUnpaid);

                                        if(mysqli_num_rows($res_selectUnpaid) > 0){
                                            while($row = mysqli_fetch_array($res_selectUnpaid)){
                                                $counter_unpaid = $counter_unpaid + 1;
                                            }
                                        }

                                        echo "<span>".$counter_unpaid."</span>";
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="dashboard-flex-container">
                        <div class="dashboard-flex-items">
                            <div class="dashboard-flex-header">
                                <span>Shipping</span>
                            </div>
                            <div class="dashboard-flex-info">
                                <div>
                                    <?php
                                        $counter_unpaid = 0;

                                        $sql_selectUnpaid = "SELECT * FROM cart WHERE status='Ongoing Shipment'";
                                        $res_selectUnpaid = mysqli_query($conn, $sql_selectUnpaid);

                                        if(mysqli_num_rows($res_selectUnpaid) > 0){
                                            while($row = mysqli_fetch_array($res_selectUnpaid)){
                                                $counter_unpaid = $counter_unpaid + 1;
                                            }
                                        }

                                        echo "<span>".$counter_unpaid."</span>";
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="dashboard-flex-items">
                            <div class="dashboard-flex-header">
                                <span>Completed</span>
                            </div>
                            <div class="dashboard-flex-info">
                                <div>
                                    <?php
                                        $counter_unpaid = 0;

                                        $sql_selectUnpaid = "SELECT * FROM cart WHERE status='Order Received'";
                                        $res_selectUnpaid = mysqli_query($conn, $sql_selectUnpaid);

                                        if(mysqli_num_rows($res_selectUnpaid) > 0){
                                            while($row = mysqli_fetch_array($res_selectUnpaid)){
                                                $counter_unpaid = $counter_unpaid + 1;
                                            }
                                        }

                                        echo "<span>".$counter_unpaid."</span>";
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </section>

    <!-- <footer>
        <span><i class='bx bx-copyright icon'></i> Copyright 2021</spam>
    </footer> -->
</body>

</html>