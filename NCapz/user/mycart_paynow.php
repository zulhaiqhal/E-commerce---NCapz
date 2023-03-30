<?php
    //START A SESSION
    session_start();

    include('../server.php');

    $uid = "";

    if(isset($_SESSION["member"]) === true){
        //SET USER INFORMATION
        $uid = $_SESSION['uid'];
        
        $sql_userInformation = "SELECT * FROM user WHERE uid='$uid'";
        $res_userInformation = mysqli_query($conn, $sql_userInformation);
        $row_userInformation = mysqli_fetch_array($res_userInformation);

        $username = $row_userInformation['username'];
    }

    $cartID = $_REQUEST['cartID'];

    if(empty($cartID)){
        header("location: ../user/mycart.php");
    }

    //TO GET AMOUNT OF UNPAID CART
    $totalPrice = 0;
    $cartAmount = 0;
    
    $sql_getCartAmount = "SELECT * FROM cart WHERE uid='$uid' AND status='unpaid'";
    $res_getCartAmount = mysqli_query($conn, $sql_getCartAmount);

    if(mysqli_num_rows($res_getCartAmount) > 0){
        while($row = mysqli_fetch_array($res_getCartAmount)){
            $cartAmount = $cartAmount + 1;
        }
    }

    $submitErr = $submitOut = "";

    echo $submitErr;
    echo $submitOut;

    if(isset($_POST['submit'])){
        $address = $_POST['address'];

        if(!empty($address)){
            $Date = date("Y-m-d");

            $sql_update = "UPDATE cart SET status='paid', address='$address', date='$Date' WHERE cart_id='$cartID'";

            if(mysqli_query($conn, $sql_update)){
                header("location: ../user/to_ship.php");
            }else{
                $submitErr = "Ops! Something Wrong!";
            }
        }else{
            $submitErr = "Invalid Input!";
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
                <div class="nav-bar-items" onclick="window.location='homepage.php'">
                    <p>Home</p>
                </div>

                <div class="nav-bar-items" onclick="window.location='product.php'">
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
                <i onclick="window.location='mycart.php'" class='bx bx-cart'></i>
                <span id="cart-items-circle">
                    <?php echo $cartAmount; ?>
                </span>
            </div>
        </div>
    </header>

    <?php if(empty($uid)){ ?>
        <div class="form-popup" id="myForm">
            <form action="login_member.php" class="form-container">
                <h1>Login</h1>

                <label for="email"><b>Email</b></label>
                <input type="text" placeholder="Enter Email" name="email" required>

                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="psw" required>

                <p id="form-popup-register">Don't have account? <a href="register.php" style="text-decoration: none; color: black;"><b>Register Here</b></a></p>

                <button type="submit" class="btn">Login</button>
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
            </form>
        </div>
    <?php }else{ ?>
        <div style="width: 220px; right: 11%;" class="form-popup" id="myForm">
            <form action="logout.php" class="form-container">
                <div onclick="window.location='mycart.php'" id="form-container-items"><span>My Purchase</span></div>
                <div onclick="window.location='myprofile.php'" id="form-container-items"><span>Profile</span></div>

                <button type="submit" class="btn">Logout</button>
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
            </form>
        </div>
    <?php } ?>

    <section>
        <br>
        <p id="product-header">Checkout</p>
        <br>
        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <div class="mycart-paynow-container">
                <div id="mycart-paynow-address-header">
                    <span>Address</span>
                </div>
                <div class="mycart-paynow-payment-details">
                    <br><br>
                    <textarea required name="address" id="paynow-address"></textarea>
                </div>
                <br>
            </div>

            <br><br>

            <div class="mycart-paynow-container">
                <div id="mycart-paynow-address-header">
                    <span>Order Details</span>
                </div>
                <div class="mycart-paynow-payment-details2">
                    <br><br>
                    <table id="styling-table2">
                        <tr>
                            <th style="width: 63px;">Product</th>
                            <th></th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th style="text-align: right;">Total</th>
                        </tr>
                        <?php
                            $sql_cartInformation = "SELECT * FROM cart WHERE cart_id='$cartID'";
                            $res_cartInformation = mysqli_query($conn, $sql_cartInformation);
                            $row_cartInformation = mysqli_fetch_array($res_cartInformation);

                            //THIS IS PRODUCT INFORMATION
                            $productID = $row_cartInformation['product_id'];

                            $sql_productInformation = "SELECT * FROM product WHERE product_id='$productID'";
                            $res_productInformation = mysqli_query($conn, $sql_productInformation);
                            $row_productInformation = mysqli_fetch_array($res_productInformation);

                            $productPrice = $row_productInformation['price'];
                            $productAmount = $row_cartInformation['amount'];
                            $totalPrice = $productAmount * $productPrice;

                            $totalPrice = number_format($totalPrice, 2);

                            echo "<tr>";
                                echo "<td><image style='width: 60px; height: 60px;' src='../resource/product_pic/".$row_productInformation['picture']."'></image></td>";
                                echo "<td>".$row_productInformation['name']."</td>";
                                echo "<td>RM ".$row_productInformation['price']."</td>";
                                echo "<td>".$row_cartInformation['amount']."</td>";
                                echo "<td style='text-align: right;'>RM ".$totalPrice."</td>";
                            echo "</tr>";

                        ?>
                    </table>
                    <br>
                    <p id="checkout-subtotal">Subtotal: RM <?php echo $totalPrice; ?></p>
                    <input type="submit" value="Place Order" name="submit" id="checkout-btn"></input>
                    <br><br>
                </div>
                <br>
            </div>
        </form>
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