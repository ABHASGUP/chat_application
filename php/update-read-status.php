<?php
session_start();
if(isset($_SESSION['unique_id'])){
    include_once "../config.php";
    
    $message_ids = json_decode($_POST['message_ids']);
    $user_id = $_SESSION['unique_id'];
    
    if(!empty($message_ids)) {
        $message_ids_str = implode(',', array_map('intval', $message_ids));
        
        $sql = "INSERT INTO message_status (msg_id, user_id, status)
                SELECT msg_id, {$user_id}, 'read'
                FROM messages 
                WHERE msg_id IN ({$message_ids_str})
                ON DUPLICATE KEY UPDATE status = 'read'";
        
        if(mysqli_query($conn, $sql)){
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['status' => 'success', 'message' => 'No messages to update']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
}
?>
