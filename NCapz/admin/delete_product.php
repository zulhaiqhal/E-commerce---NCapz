<?php
    //START A SESSION
    session_start();

    include('../server.php');

    //CHECK IF THE USER IS LOGGED IN
    if($_SESSION["admin"] !== true){
        header("location: login.php");
        exit;
    }

    $permission = $_SESSION["permission"];
    $uid = $_SESSION["uid"];

    $productID = $_REQUEST['productID'];

    $sql_delete = "DELETE FROM product WHERE product_id='$productID'";
    
    if(mysqli_query($conn, $sql_delete)){
        header("location: product_manage.php?status=Product Successfully Deleted!");
    }else{
        header("location: product_manage.php?status=Ops! Something wrong!");
        echo $categoryID;
    }

    mysqli_close($conn);

?>