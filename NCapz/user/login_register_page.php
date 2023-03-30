<?php
    //START A SESSION
    session_start();

    include('../server.php');

    //CHECK IF USER IS ALREADY LOGIN
    if(!empty($_SESSION["member"])){
        header("location: ../index.php");
        exit;
    }

    $staffID = $username = $password = $permission = $out = "";

    if(isset($_POST["login"])){
        $staffID = $_POST['email'];
        $password = $_POST['psw'];
        $staffID = addslashes($staffID);
        $password = addslashes($password);

        // echo $password;
        // echo $staffID;

        $sql_validate = "SELECT * FROM user WHERE email='$staffID' AND password='$password' AND permission='member'";
        $res_validate = mysqli_query($conn, $sql_validate);

        if(mysqli_num_rows($res_validate) > 0){
            session_start();
            while($row = mysqli_fetch_array($res_validate)){
                //STORE DATA IN SESSION VARIABLES
                $permission = $row["permission"];
                $uid = $row["uid"];

                $_SESSION["member"] = true;
                $_SESSION["permission"] = $permission;
                $_SESSION["uid"] = $uid;

                header("location: ../index.php");
            }
        }else{
            echo '<script>alert("Wrong email or password!")</script>';
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
    <nav id="register-login-nav">
        <div onclick="window.location='../index.php'" class="register-items">
            <image id="header-logo" src="../resource/images/cap logo.jpg">

            </image>
            <h1 style="margin-top: 25px;" id="header-title"><span id="eco-green">N</span> Capz <span id="login-register-title">| Log In</span></h1>
        </div>

        <div class="register-items">

        </div>
    </nav>

    <section id="login-register-section" class="flex">
        <div class="login-register-items">
            
        </div>

        <div class="login-register-items  flex flex-center">
            <div class="login-container">
                <p id="login-container-header">Log In | Member</p>

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
                        <a href="../admin/">Admin Login</a>
                    </div>
                </div>

                <hr>
                
                <div class="flex flex-center">
                    <span id="new-to-eco">New to NCapz?</span><span id="sign-up"><a id="sign-up" href="../user/register.php">&nbsp;Sign Up</a ></span>
                </div>

            </div>
        </div>
    </section>

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

<script>

</script>