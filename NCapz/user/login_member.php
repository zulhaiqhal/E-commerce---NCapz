<?php
    //START A SESSION
    session_start();

    //CHECK IF THE USER IS LOGGED IN
    if(isset($_SESSION["member"]) === true){
        header("location: ../index.php");
        exit;
    }

    include('../server.php');
    
    $staffID = $username = $password = $permission = $out = "";

    $staffID = $_REQUEST['email'];
    $password = $_REQUEST['psw'];
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
        header("location: ../index.php?status=Login Failed! Incorrect ID or Password!");
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
    <title>System Admin Login</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/all.css" />
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>

<body id="register_background">
    <div class="register_logo">
        <img id="register_logo" src="../css/images/login_admin.png"></img>
    </div>

    <p id="register_title">System Admin Login</p>
    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="id_field">
            <div style="text-align: center;" class="login_field_left">
                <i style="font-size: 2rem; color: white; margin-top: 10px;" class="bx bx-user icon"></i>
            </div>

            <div class="login_field_right">
                <input id="username" name="staff_id" required type="username" placeholder="Admin Email"></input>
            </div>
        </div>

        <div class="password_field">
            <div style="text-align: center;" class="login_field_left">
                <i style="font-size: 2rem; color: white; margin-top: 10px;" class="bx bx-lock icon"></i>
            </div>

            <div class="login_field_right">
                <input id="password" name="password" required type="password" placeholder="Password"></input>
            </div>
        </div>

        <div style="width: 350px; margin: auto;">
            <input id="sign_in_button" name="login" value="SIGN IN" type="submit"></input>
        </div>

        <p style="text-align: center; color: red;"><?php echo $out; ?></p>
    </form>
    <p style="color: white; text-align: center;">Forget Password? Please contact school admin.</p>
    <a href="../user/login.php"><p style="color: white; text-align: center; font-size: 1.2rem;">Back</p></a>
</body>

</html>