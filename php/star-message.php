<?php
session_start();
if(isset($_SESSION['unique_id'])){
    include_once "../config.php";
    
    $msg_id = mysqli_real_escape_string($conn, $_POST['msg_id']);
    $user_id = $_SESSION['unique_id'];
    
    // Check if message is already starred
    $check_sql = "SELECT * FROM starred_messages WHERE msg_id = {$msg_id} AND user_id = {$user_id}";
    $check_result = mysqli_query($conn, $check_sql);
    
    if(mysqli_num_rows($check_result) > 0) {
        // Message is already starred, unstar it
        $sql = "DELETE FROM starred_messages WHERE msg_id = {$msg_id} AND user_id = {$user_id}";
        $status = 'unstarred';
    } else {
        // Message is not starred, star it
        $sql = "INSERT INTO starred_messages (msg_id, user_id) VALUES ({$msg_id}, {$user_id})";
        $status = 'starred';
    }
    
    if(mysqli_query($conn, $sql)){
        echo json_encode(['status' => 'success', 'action' => $status]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
}
?>
