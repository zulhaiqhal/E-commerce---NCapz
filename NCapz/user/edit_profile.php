<?php
    //START A SESSION
    session_start();

    include('../server.php');

    $uid = $username = $submitErr = $submitOut = $email = $joinDate = $profile_pic = $permission = "";

    if(empty($_SESSION["member"])){
        header("location: ../user/login_register_page.php");
        exit;
    }

    if(isset($_SESSION["member"]) === true){
        //SET USER INFORMATION
        $uid = $_SESSION['uid'];
        
        $sql_userInformation = "SELECT * FROM user WHERE uid='$uid'";
        $res_userInformation = mysqli_query($conn, $sql_userInformation);
        $row_userInformation = mysqli_fetch_array($res_userInformation);

        $username = $row_userInformation['username'];
        $email = $row_userInformation['email'];
        $joinDate = $row_userInformation["join_date"];
        $profile_pic = $row_userInformation["profile_pic"];
        $permission = $row_userInformation["permission"];
    }

    //TO GET AMOUNT OF UNPAID CART
    $cartAmount = 0;
    
    $sql_getCartAmount = "SELECT * FROM cart WHERE uid='$uid' AND status='unpaid'";
    $res_getCartAmount = mysqli_query($conn, $sql_getCartAmount);

    if(mysqli_num_rows($res_getCartAmount) > 0){
        while($row = mysqli_fetch_array($res_getCartAmount)){
            $cartAmount = $cartAmount + 1;
        }
    }

    //TO GET AMOUNT OF UNPAID CART
    $cartAmount = 0;
    
    $sql_getCartAmount = "SELECT * FROM cart WHERE uid='$uid' AND status='unpaid'";
    $res_getCartAmount = mysqli_query($conn, $sql_getCartAmount);

    if(mysqli_num_rows($res_getCartAmount) > 0){
        while($row = mysqli_fetch_array($res_getCartAmount)){
            $cartAmount = $cartAmount + 1;
        }
    }


    //FOR UPDATE PROFILE
    if(isset($_POST["submit"])){

        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        $username = addslashes($username);
        $email = addslashes($email);
        $password = addslashes($password);

        //CHECK PASSWORD
        $sql_checkPassword = "SELECT * FROM user WHERE uid='$uid' AND password='$password'";
        $res_checkPassword = mysqli_query($conn, $sql_checkPassword);
        
        if(mysqli_num_rows($res_checkPassword) > 0){

            $target_dir = "../resource/profile_pic/";
            $target_file = $target_dir.basename($_FILES['file']['name']);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $uniqid = date('YmdHis');
            $new_image_name = $uniqid.".".$imageFileType;
            $real_target_dir = "../resource/profile_pic/".$new_image_name;

            if(!empty($target_file)){
                if($_FILES['file']['size'] > 8000000){
                    $submitErr = "File Size Too Big!";
                }

                //CHECK FILE TYPE
                if(!empty(basename($_FILES['file']['name']))){
                    if($imageFileType != "gif" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "jpg"){
                        $submitErr = "Invalid File Type!";
                    }
                }

                if(empty($submitErr) && !empty($target_file)){
                    if(move_uploaded_file($_FILES['file']['tmp_name'],$real_target_dir)){
                        $bCover = basename($_FILES['file']['name']);

                        $sql_updateData = "UPDATE user SET profile_pic = '$new_image_name' WHERE uid='$uid'";

                        if(mysqli_query($conn, $sql_updateData)){
                            $submitOut = "Successfully Updated!";
                        }else{
                            $submitErr = "Ops! Something Wrong! Please check your database!";
                        }
                    }
                }
            }

            if(!empty($username)){
                //CHECK FOR DUP Product
                $sql_checkDupName = "SELECT * FROM user WHERE username='$username' AND uid != '$uid'";
                $res_checkDupName = mysqli_query($conn, $sql_checkDupName);

                if(mysqli_num_rows($res_checkDupName) > 0){
                    $submitErr = "Username has been used!";
                }

                if(empty($submitErr)){
                    $sql_updateData = "UPDATE user SET username = '$username' WHERE uid='$uid'";

                    if(mysqli_query($conn, $sql_updateData)){
                        $submitOut = "Successfully Updated!";
                    }else{
                        $submitErr = "Ops! Something Wrong! Please check your database!";
                    }
                }
            }

            if(!empty($email)){
                $sql_updateData = "UPDATE user SET email = '$email' WHERE uid='$uid'";

                if(mysqli_query($conn, $sql_updateData)){
                    $submitOut = "Successfully Updated!";
                }else{
                    $submitErr = "Ops! Something Wrong! Please check your database!";
                }
            }
        }else{
            echo '<script>alert("Wrong Password!")</script>';
        }

        if(!empty($submitErr)){
            echo '<script>alert("'.$submitErr.'")</script>';
        }

        if($submitOut == "Successfully Updated!"){
            echo '<script>alert("Profile Updated! Please refresh the page!")</script>';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/javascript.js"></script>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="body">
    <header>
        <div class="container-wrapper">
            <div onclick="window.location='../index.php'" id="header-logo-contianer">
                <image id="header-logo" src="../resource/images/cap logo.jpg">

                </image>
                <h2 id="header-title">NCapz</h2>
            </div>

            <div class="nav-bar">
                <div class="nav-bar-items" onclick="window.location='../index.php'">
                    <p>Home</p>
                </div>

                <div class="nav-bar-items" onclick="window.location='../user/product.php'">
                    <p>Products</p>
                </div>

                <div class="nav-bar-items" onclick="window.location='../user/profile.php'">
                    <p>Profile</p>
                </div>
            </div>

            <div class="cart-items">
                <?php if(empty($uid)){ ?>
                    <span id="login-register" onclick="window.location='../user/login_register_page.php'">Login/Register</span>
                <?php }else{ ?>
                    <span id="login-register" onclick="openForm()"><?php echo $username;?></span>
                <?php } ?>
                <i onclick="window.location='../user/mycart.php'" class='bx bx-cart'></i>
                <span id="cart-items-circle">
                    <?php echo $cartAmount; ?>
                </span>
            </div>
        </div>
    </header>

    <?php if(empty($uid)){ ?>
        <div class="form-popup" id="myForm">
            <form action="../user/login_member.php" class="form-container">
                <h1>Login</h1>

                <label for="email"><b>Email</b></label>
                <input type="text" placeholder="Enter Email" name="email" required>

                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="psw" required>

                <p id="form-popup-register">Don't have account? <a href="../user/register.php" style="text-decoration: none; color: black;"><b>Register Here</b></a></p>

                <button type="submit" class="btn">Login</button>
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
            </form>
        </div>
    <?php }else{ ?>
        <div style="width: 220px; right: 11%;" class="form-popup" id="myForm">
            <form action="../user/logout.php" class="form-container">
                <!-- Uncomment to enable the feature, profile feature not working ya :3 -->
                <!-- <div onclick="window.location='mycart.php'" id="form-container-items"><span>My Purchase</span></div>
                <div onclick="window.location='myprofile.php'" id="form-container-items"><span>Profile</span></div> -->

                <button type="submit" class="btn">Logout</button>
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
            </form>
        </div>
    <?php } ?>

    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <section class="flex flex-center">
            <div class="profile-container flex">
                <div class="profile-item-left">
                    <image src="../resource/profile_pic/<?php echo $profile_pic; ?>" id="previewImg" class="profile-item-picture">

                    </image>
                    <br><br>
                    <input id="profile-file-input" type="file" name="file" onchange="previewFile(this);" />      
                </div>

                <div class="profile-item-right">
                    <label>Username: </label>
                    <input type="text" required name="username" value="<?php echo $username; ?>" />
                    <br>
                    <label>Email: </label>
                    <input type="email" required name="email" value="<?php echo $email; ?>" />
                    <br>
                    <label>Password: </label>
                    <input type="password" required name="password" placeholder="Please enter your password" />
                    <a href="edit_password.php" style="text-decoration: none; font-size: 0.8rem;">(Change Password)</a>

                    <div class="flex flex-center">
                        <input type="submit" value="Save Change" name="submit" class="button-active" />
                    </div>
                </div>
            </div>
        </section>
    </form>

    <section class="footer">
        <div id="footer-item-1">
            <span class="footer-item-header">Pages</span>
            <br><br>
            <span class="footer-item-list"><a href="../index.php">Home</a></span>
            <br>
            <span class="footer-item-list"><a href="../user/register.php">Register</a></span>
            <br>
            <span class="footer-item-list"><a href="../user/login_register_page.php">Login</a></span>
            <br>
            <span class="footer-item-list"><a href="../user/profile.php">Profile</a></span>
            <br>
            <span class="footer-item-list"><a href="../user/mycart.php">My Cart</a></span>
        </div>

        <div id="footer-item-2">
            <span class="footer-item-header">About Us</span>
            <br><br>
            <span class="footer-item-list">Welcome to NCapz! We provide delivery services for all around Malaysia. With over 10 years of experience, we asure you with the best product quality and services.</span>
        </div>

        <div id="footer-item-3">
            <span class="footer-item-header">Contact</span>
            <br><br>
            <span class="footer-item-list">Fax: (+60) 18-975 5002</span>
            <br>
            <span class="footer-item-list">Email: admin@NCapz.com</span>
        </div>
    </section>

    <footer>
        <span><i class='bx bx-copyright icon'></i> Copyright 2023</spam>
    </footer>
</body>

</html>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script>
    function previewFile(input){
        var file = $("input[type=file]").get(0).files[0];

        if(file){
            var reader = new FileReader();

            reader.onload = function(){
                $("#previewImg").attr("src",reader.result);
                $("#previewImg").hide();
                $("#previewImg").fadeIn(650);
            }

            reader.readAsDataURL(file);
        }
    }
</script>