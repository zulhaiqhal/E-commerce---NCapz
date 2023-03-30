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

    $submitErr = $submitOut = $categoryName = "";

    if(isset($_POST["submit"])){
        
        $categoryName = $_POST['category-name'];

        if(!empty($categoryName)){
            //CHECK FOR NAME DUP
            $sql_checkDup = "SELECT * FROM categories WHERE name='$categoryName'";
            $res_checkDup = mysqli_query($conn, $sql_checkDup);

            if(mysqli_num_rows($res_checkDup) > 0){
                $submitErr = "Category already exist!";
            }

            if(empty($submitErr)){
                //UPLOAD to DMBS
                $sql_insert = "INSERT INTO categories (name) VALUES ('$categoryName')";

                if(mysqli_query($conn, $sql_insert)){
                    $submitOut = "New category successfully added!";
                }else{
                    $submitErr = "Ops! Something Wrong!";
                }
            }

        }else{
            $submitErr = "Field is empty!";
        }

    }
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
                <div style="background-color: rgb(218, 218, 218); color: #282730;" class="nav-menu-items">
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
                <a href="category_information.php" style="text-decoration: none;">
                    <div class="container-nav-bar-items">
                        <span>Category Information</span>
                    </div>
                </a>

                <div class="container-nav-bar-items">
                    <span>|</span>
                </div>

                <div style="border-bottom: 2px solid white;" class="container-nav-bar-items">
                    <span>Create Category</span>
                </div>

                <div class="container-nav-bar-items">
                    <span>|</span>
                </div>

                <a style="text-decoration: none;" href="category_manage.php">
                    <div class="container-nav-bar-items">
                        <span>Manage Category</span>
                    </div>
                </a>
            </div>

            <div class="container-items">
                <div style="width: 50%; margin: auto;">
                    <br>
                    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                        <label for="fname">Category Name</label>
                        <input type="text" id="fname" name="category-name">
                        <br><br>
                        <center>
                            <input type="submit" name="submit" id="submit-btn" value="Submit">
                            <p style="text-align: center; color: red;"><?php echo $submitErr; ?></p>
                            <p style="text-align: center; color: green;"><?php echo $submitOut; ?></p>
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- <footer>
        <span><i class='bx bx-copyright icon'></i> Copyright 2021</spam>
    </footer> -->
</body>

</html>