<?php
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "../config.php";
        $unique_id = $_SESSION['unique_id'];
        
        // Update user status to "Offline now"
        $sql = mysqli_query($conn, "UPDATE users SET status = 'Offline now' WHERE unique_id = {$unique_id}");
        if($sql){
            session_unset();
            session_destroy();
            header("location: ../login.php");
            exit;
        }
    } else {
        header("location: ../login.php");
        exit;
    }
?>
