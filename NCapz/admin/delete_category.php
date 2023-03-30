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

    $categoryID = $_REQUEST['categoryID'];

    $sql_delete = "DELETE FROM categories WHERE categories_id='$categoryID'";
    
    if(mysqli_query($conn, $sql_delete)){
        header("location: category_manage.php?status=Category Successfully Deleted!");
    }else{
        header("location: category_manage.php?status=Ops! Something wrong!");
        echo $categoryID;
    }

    mysqli_close($conn);

?>