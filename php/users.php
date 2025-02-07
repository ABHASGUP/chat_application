<?php
    session_start();
    include_once "../config.php";
    
    if(!isset($_SESSION['unique_id'])){
        echo "Not logged in";
        exit;
    }
    
    $outgoing_id = $_SESSION['unique_id'];
    $sql = "SELECT * FROM users WHERE NOT unique_id = {$outgoing_id} ORDER BY user_id DESC";
    $query = mysqli_query($conn, $sql);
    $output = "";
    
    if(mysqli_num_rows($query) == 0){
        $output .= "No users are available to chat";
    } elseif(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['unique_id']}
                    OR outgoing_msg_id = {$row['unique_id']}) AND (outgoing_msg_id = {$outgoing_id} 
                    OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";
            $query2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($query2);
            
            $you = "";
            if(mysqli_num_rows($query2) > 0){
                $result = htmlspecialchars($row2['msg']);
                $you = ($outgoing_id == $row2['outgoing_msg_id']) ? "You: " : "";
            }else{
                $result = "No message available";
            }
            
            $output .= '<a href="/chat_application/chat.php?user_id='.$row['unique_id'].'">
                        <div class="content">
                            <img src="/chat_application/images/'.htmlspecialchars($row['img']).'" alt="">
                            <div class="details">
                                <span>'.htmlspecialchars($row['fname'] . " " . $row['lname']).'</span>
                                <p>'.$you . $result.'</p>
                            </div>
                        </div>
                        <div class="status-dot '.($row['status'] == "Active now" ? "online" : "offline").'">
                            <i class="fas fa-circle"></i>
                        </div>
                    </a>';
        }
    }
    echo $output;
?>
