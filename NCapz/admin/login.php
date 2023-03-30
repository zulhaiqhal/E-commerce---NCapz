<?php
    //START A SESSION
    session_start();

    //CHECK IF THE USER IS LOGGED IN
    if(isset($_SESSION["admin"]) === true){
        header("location: dashboard.php");
        exit;
    }

    include('../server.php');
    
    $staffID = $username = $password = $permission = $out = "";

    if(isset($_POST['login'])){
        $staffID = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['psw']);
        $staffID = addslashes($staffID);
        $password = addslashes($password);

        $sql_validate = "SELECT * FROM user WHERE email='$staffID' AND password='$password' AND permission='System Admin'";
        $res_validate = mysqli_query($conn, $sql_validate);

        if(mysqli_num_rows($res_validate) > 0){
            session_start();
            while($row = mysqli_fetch_array($res_validate)){
                //STORE DATA IN SESSION VARIABLES
                $username = $row["username"];
                $permission = $row["permission"];
                $uid = $row["uid"];

                $_SESSION["admin"] = true;
                $_SESSION["member"] = true;
                $_SESSION["permission"] = $permission;
                $_SESSION["uid"] = $uid;
                $_SESSION["username"] = $username;

                header("location: ../admin/index.php");
            }
        }else{
            $out = "Login Failed! Incorrect ID or Password!";
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

<body id="login-register-body" class="body">
    <nav onclick="window.location='../index.php'" id="register-login-nav">
        <div class="register-items">
            <image id="header-logo" src="../resource/images/cap logo.jpg">

            </image>
            <h1 style="margin-top: 25px;" id="header-title"><span id="eco-green">Zeus</span> Vape <span id="login-register-title">| Log In</span></h1>
        </div>

        <div class="register-items">

        </div>
    </nav>

    <section id="login-register-section" class="flex">
        <div class="login-register-items">
            
        </div>

        <div class="login-register-items  flex flex-center">
            <div class="login-container">
                <p id="login-container-header">Log In | <span style="color: red;">Admin</span></p>

                <hr style="margin-top: 1rem; margin-bottom: 1rem;">

                <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                    <label for="">Email:</label>
                    <br>
                    <input class="login-register-input" name="email" type="email" placeholder="Email"></input>
                    <br><br>
                    <label for="">Password:</label>
                    <input class="login-register-input" name="psw" type="password" placeholder="Password"></input>

                    <input name="login" class="login-register-submit" value="LOG IN" type="submit"></input>
                </form>
                <br>
                <div class="flex">
                    <div class="login-register-forget-password">
                        <a href="../user/register.php">Register Here</a>
                    </div>

                    <div class="login-register-forget-password">
                        <a href="../user/login_register_page.php">Member Login</a>
                    </div>
                </div>

                <hr>
                
                <div class="flex flex-center">
                    <span id="new-to-eco">New to Eco Land?</span><span id="sign-up"><a id="sign-up" href="../user/register.php">&nbsp;Sign Up</a ></span>
                </div>

            </div>
        </div>
    </section>

    <section class="footer">
        <div id="footer-item-1">
            <span class="footer-item-header">Pages</span>
            <br><br>
            <span class="footer-item-list"><a href="index.php">Home</a></span>
            <br>
            <span class="footer-item-list"><a href="user/register.php">Register</a></span>
            <br>
            <span class="footer-item-list"><a href="user/login_register_page.php">Login</a></span>
            <br>
            <span class="footer-item-list"><a href="user/profile.php">Profile</a></span>
            <br>
            <span class="footer-item-list"><a href="user/mycart.php">My Cart</a></span>
        </div>

        <div id="footer-item-2">
            <span class="footer-item-header">About Us</span>
            <br><br>
            <span class="footer-item-list">Welcome to Eco Land! We provide the best of the best with our fresh vegetable and fruits! All products is from Malaysia growth by the locals farmers.</span>
        </div>

        <div id="footer-item-3">
            <span class="footer-item-header">Contact</span>
            <br><br>
            <span class="footer-item-list">Fax: (+60) 14-256 6635</span>
            <br>
            <span class="footer-item-list">Email: admin@eco_land.com</span>
        </div>
    </section>

    <footer>
        <span><i class='bx bx-copyright icon'></i> Copyright 2022</spam>
    </footer>
</body>

</html>

<script>

</script>