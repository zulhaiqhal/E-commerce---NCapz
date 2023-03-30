<?php
    //START A SESSION
    session_start();

    include('../server.php');

    $username = $permission = $staffID = $profilePicture = $submitErr = $submitOut = "";

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
        
        $productName = $_POST['product-name'];
        $productPrice = $_POST['product-price'];
        $productCategory = $_POST['product-category'];
        $productDescription = $_POST['product-description'];

        $productName = addslashes($productName);
        $productPrice = addslashes($productPrice);
        $productCategory = addslashes($productCategory);
        $productDescription = addslashes($productDescription);

        if(!empty($productName) || !empty($productPrice) || !empty($productCategory) || !empty($productDescription)){

            $target_dir = "../resource/product_pic/";
			$target_file = $target_dir.basename($_FILES['file']['name']);
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$uniqid = date('YmdHis');
			$new_image_name = $uniqid.".".$imageFileType;
			$real_target_dir = "../resource/product_pic/".$new_image_name;

            if($_FILES['file']['size'] > 8000000){
                $submitErr = "File Size Too Big!";
            }

            //CHECK FILE TYPE
            if(!empty(basename($_FILES['file']['name']))){
                if($imageFileType != "gif" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "jpg"){
                    $submitErr = "Invalid File Type!";
                }
            }

            //CHECK FOR DUP Product
            $sql_checkDupName = "SELECT * FROM product WHERE name='$productName'";
            $res_checkDupName = mysqli_query($conn, $sql_checkDupName);

            if(mysqli_num_rows($res_checkDupName) > 0){
                $submitErr = "Product name already exist!";
            } 

            if(empty($submitErr)){
                if(move_uploaded_file($_FILES['file']['tmp_name'],$real_target_dir)){
					$bCover = basename($_FILES['file']['name']);

					$sql_insertData = "INSERT INTO product (name, picture, price, description, categories_id) VALUES ('$productName', '$new_image_name', '$productPrice', '$productDescription', '$productCategory')";

                    if(mysqli_query($conn, $sql_insertData)){
                        $submitOut = "Successfully Added!";
                    }else{
                        $submitErr = "Ops! Something Wrong! Please check your database!";
                    }
				}
            }
        }else{
            $submitErr = "Information missing!";
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
                <div style="background-color: rgb(218, 218, 218); color: #282730;" class="nav-menu-items">
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
                <a href="product_information.php" style="text-decoration: none;">
                    <div class="container-nav-bar-items">
                        <span>Product Information</span>
                    </div>
                </a>

                <div class="container-nav-bar-items">
                    <span>|</span>
                </div>

                <a href="product_create.php" style="text-decoration: none;">
                    <div style="border-bottom: 2px solid white;" class="container-nav-bar-items">
                        <span>Create Product</span>
                    </div>
                </a>

                <div class="container-nav-bar-items">
                    <span>|</span>
                </div>

                <a style="text-decoration: none;" href="product_manage.php">
                    <div class="container-nav-bar-items">
                        <span>Manage Product</span>
                    </div>
                </a>
            </div>

            <div class="container-items">
                <div style="width: 100%; margin: auto;">
                    <br>
                    <form action="<?php $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                        <div class="create-product-container">
                            <div class="left-create-product-container">
                                <div class="avatar-upload">
                                    <div class="avatar-edit">
                                        <input type="file" name="file" id="imageUpload" onchange="previewFile(this);" accept=".png, .jpg, .jpeg" />
                                        <label for="imageUpload"></label>
                                    </div>
                                    <div style="overflow: hidden;" class="avatar-preview">
                                        <img id="previewImg" style="width: 200px; height: 200px;">
                                    </div>
                                </div>
                            </div>

                            <div class="right-create-product-container">
                                <label for="product-name">Product Name</label>
                                <input type="text" id="product-name" name="product-name">
                                <br>
                                <label for="product-price">Product Price (RM)</label>
                                <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" id="product-price" min="0.00" step="0.10" name="product-price">
                                <br>
                                <label for="category">Category</label>
                                <?php
                                    $sql_selectCategory = "SELECT * FROM categories";
                                    $res_selectCategory = mysqli_query($conn, $sql_selectCategory);

                                    if(mysqli_num_rows($res_selectCategory) > 0){
                                        echo "<select name='product-category' id='category-selection'>";
                                        while($row = mysqli_fetch_array($res_selectCategory)){
                                            echo "<option value='".$row['categories_id']."'>".$row['name']."</option>";
                                        }
                                        echo "</select>";
                                    }
                                ?>
                            </div>
                        </div>
                        <div style="width: 80%; margin: auto;">
                            <hr><br>
                            <div style="width: 60%; margin: auto;">
                                <label for="description">Product Description</label>
                                <br>
                                <textarea id="textarea-product-description" name="product-description"></textarea>
                            </div>
                            <br>
                            <center>
                                <input type="submit" id="submit-btn" name="submit" value="Submit"></input>
                                <p style="color: green;"><?php echo $submitOut; ?></p>
                                <p style="color: red;"><?php echo $submitErr; ?></p>
                            </center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- <footer>
        <span><i class='bx bx-copyright icon'></i> Copyright 2021</spam>
    </footer> -->
</body>

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

</html>