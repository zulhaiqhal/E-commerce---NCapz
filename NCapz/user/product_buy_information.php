<?php
    //START A SESSION
    session_start();

    include('../server.php');

    $productID = $_REQUEST['productID'];

    $sql_productInformation = "SELECT * FROM product WHERE product_id='$productID'";
    $res_productInformation = mysqli_query($conn, $sql_productInformation);
    $row_productInformation = mysqli_fetch_array($res_productInformation);

    $uid = $submitOut = "";

    if(isset($_SESSION["member"]) === true){
        //SET USER INFORMATION
        $uid = $_SESSION['uid'];
        
        $sql_userInformation = "SELECT * FROM user WHERE uid='$uid'";
        $res_userInformation = mysqli_query($conn, $sql_userInformation);
        $row_userInformation = mysqli_fetch_array($res_userInformation);

        $username = $row_userInformation['username'];
    }

    //Ops something wrong quote is normally something wrong in DBMS ya

    if(isset($_POST['submit'])){
        $amount = $_POST['amount'];

        $sql_insertData = "INSERT INTO cart (uid, product_id, amount) VALUES ('$uid', '$productID', '$amount')";
        
        if(mysqli_query($conn, $sql_insertData)){
            $submitOut = "Item added to cart!";
        }else{
            $submitOut = "Ops! Something Wrong!";
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
        <div onclick="window.location='../index.php'" class="container-wrapper">
            <div id="header-logo-contianer">
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
                <button type="submit" class="btn">Logout</button>
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
            </form>
        </div>
    <?php } ?>

    <section>
        <br><br>
        <div class="product-buy-information">
            <div class="product-buy-information-left">
                <img src="../resource/product_pic/<?php echo $row_productInformation['picture']; ?>"></img>
            </div>

            <div class="product-buy-information-right">
                <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post">
                    <p id="product-buy-information-name"><?php echo $row_productInformation['name']; ?></p>
                    <p id="product-buy-information-price">RM<?php echo $row_productInformation['price']; ?></p>
                    <input oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" type="number" name="amount" value="1" min="1" max="23" placeholder="Quantity" required="">
                    <div class="product-buy-information-description">
                        <span><?php echo $row_productInformation['description']; ?></span>
                    </div>
                    <br>
                    <hr>
                    <br>
                    <center>
                        <?php if(!empty($uid)){ ?>
                            <input type="submit" name="submit" value="Add To Cart">
                        <?php }else{ ?>
                            <div onclick="window.location='../user/login_register_page.php'" id="product-buy-information-please-login">PLEASE LOGIN</div>
                        <?php } ?>
                        <p><?php echo $submitOut; ?></p>
                    </center>
                </form>
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