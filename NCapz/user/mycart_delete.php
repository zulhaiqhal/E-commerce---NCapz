<?php
    //START A SESSION
    session_start();

    include('../server.php');

    //CHECK IF THE USER IS LOGGED IN
    if($_SESSION["member"] !== true){
        header("location: ../user/login.php");
        exit;
    }

    $permission = $_SESSION["permission"];
    $uid = $_SESSION["uid"];

    $cartID = $_REQUEST['cartID'];

    $sql_delete = "DELETE FROM cart WHERE cart_id='$cartID'";
    
    if(mysqli_query($conn, $sql_delete)){
        header("location: ../user/mycart.php?status=Product Successfully Deleted!");
    }else{
        header("location: ../user/mycart.php?status=Ops! Something wrong!");
        echo $categoryID;
    }

    mysqli_close($conn);

?>