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

    <section>
        <br><br>
        <div class="mycart-nav-bar">
            <div onclick="window.location='../user/mycart.php'" style="border-bottom: 4px solid rgb(57, 128, 235);" class="mycart-nav-bar-item">
                <span>To Pay</span>
            </div>

            <div onclick="window.location='../user/to_ship.php'" class="mycart-nav-bar-item">
                <span>To Ship</span>
            </div>

            <div onclick="window.location='../user/to_received.php'" class="mycart-nav-bar-item">
                <span>To Received</span>
            </div>

            <div onclick="window.location='../user/completed.php'" class="mycart-nav-bar-item">
                <span>Completed</span>
            </div>
        </div>

        <div class="mycart-container">
            <br>
            <br>
                <?php
                    $sqll = "Select count(*) from cart WHERE uid='$uid' AND status='unpaid'";
                    $retnum = mysqli_query($conn,$sqll);
                    $count = mysqli_fetch_array($retnum, MYSQLI_NUM);
                    $rec_count = $count['0'];
                    $rec_limit = 3;
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

                    $sql2 = "Select * from cart WHERE uid='$uid' AND status='unpaid' limit $offset, $rec_limit";
                    $result = mysqli_query($conn,$sql2);
                    
                    echo "<table>";

                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){

                            $productID = $row['product_id'];

                            //PRODUCT INFORMATION
                            $sql_productInformation = "SELECT * FROM product WHERE product_id='$productID'";
                            $res_productInformation = mysqli_query($conn, $sql_productInformation);
                            $row_productInformation = mysqli_fetch_array($res_productInformation);

                            $totalPrice = 0;
                            $totalPrice = $row['amount'] * $row_productInformation['price'];
                            $totalPrice = number_format($totalPrice, 2);

                            $itemPrice = $row_productInformation['price'];
                            $itemPrice = number_format($itemPrice, 2);

                            echo "<div style='margin: auto; width: 80%; border: solid black 2px;'>";
                                echo "<div class='mycart-container-items'>";

                                    echo "<div class='mycart-container-items-left'>";
                                        echo "<image id='mycart-product-picture' src='../resource/product_pic/".$row_productInformation['picture']."'></image>";
                                    echo "</div>";

                                    echo "<div style='width: 1rem; min-width: 1rem; max-width: 1rem;'>";

                                    echo "</div>";

                                    echo "<div class='mycart-container-items-right'>";
                                        echo "<div id='mycart-item-name'>";
                                            echo "<span>".$row_productInformation['name']."</span>";
                                        echo "</div>";

                                        echo "<div id='mycart-item-description'>";
                                            echo "<span>".$row_productInformation['description']."</span>";
                                        echo "</div>";

                                        echo "<div id='mycart-item-amount'>";
                                            echo "<span>x".$row['amount']."</span>";
                                        echo "</div>";
                                    echo "</div>";

                                    echo "<div id='mycart-item-price'>";
                                        echo "<span>RM".$itemPrice."</span>";
                                    echo "</div>";
                                echo "</div>";

                                echo "<div class='mycart-item-lower'>";
                                    echo "<div class='mycart-item-lower-1'>";
                                        echo "<span>Total: RM ".$totalPrice."</span>";
                                    echo "</div>";

                                    echo "<div class='mycart-item-lower-2'>";
                                        echo "<div id='paynow-btn' onclick=\"window.location='../user/mycart_paynow.php?cartID=".$row['cart_id']."'\">Pay Now</div>";
                                        echo "<div style='margin-right: 0.5rem;' onclick=\"window.location='../user/mycart_delete.php?cartID=".$row['cart_id']."'\" id='paynow-btn'>Delete Item</div>";
                                        echo "<div style='margin-right: 0.5rem;' onclick=\"window.location='../user/product_buy_information.php?productID=".$productID."'\" id='paynow-btn'>View Item</div>";
                                    echo "</div>";
                                echo "</div>";

                            echo "</div>";
                            echo "<br><br><br>";
                        }
                    }else{
                        echo "<center>";
                            echo "<div style='width: 200px; height: 200px; margin: auto;'><image style='width: 100%; height: 100%; border-radius: 50%;' src='../resource/images/empty.png'></image></div>";
                            echo "<p id='text-align: center;'>No orders yet</p>";
                        echo "</center>";
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
            <br>
            <br>
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