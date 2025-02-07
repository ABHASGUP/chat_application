<?php
session_start();
if(isset($_SESSION['unique_id'])){
    include_once "../config.php";
    
    $msg_id = mysqli_real_escape_string($conn, $_POST['msg_id']);
    $user_id = $_SESSION['unique_id'];
    
    // Only allow deleting own messages
    $sql = "UPDATE messages 
            SET msg_type = 'deleted', 
                deleted_at = NOW() 
            WHERE msg_id = {$msg_id} 
            AND outgoing_msg_id = {$user_id}";
    
    if(mysqli_query($conn, $sql)){
        if(mysqli_affected_rows($conn) > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Cannot delete message']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
}
?>
