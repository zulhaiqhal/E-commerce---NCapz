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

    $cartID = $_REQUEST['cartID'];

    $sql_update = "UPDATE cart SET status = 'Order Received' WHERE cart_id='$cartID'";
    
    if(mysqli_query($conn, $sql_update)){
        header("location: shipment_ongoing.php?status=Product Successfully Deleted!");
    }else{
        header("location: shipment_ongoing.php?status=Ops! Something wrong!");
        echo $categoryID;
    }

    mysqli_close($conn);

?>