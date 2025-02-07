<?php 
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "../config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $output = "";
        
        $sql = "SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
                OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY msg_id";
        $query = mysqli_query($conn, $sql);
        
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                $msg = $row['msg'];
                
                if($row['outgoing_msg_id'] === $outgoing_id){
                    // Outgoing message
                    $output .= '<div class="chat outgoing">
                                <div class="details">';
                    
                    if(strpos($msg, '[image]') !== false) {
                        // Image message
                        preg_match('/\[image\](.*?)\[\/image\]/', $msg, $matches);
                        $imagePath = $matches[1];
                        $output .= '<p class="file-message">
                                    <img src="../'.$imagePath.'" alt="Shared Image" class="shared-image">
                                  </p>';
                    } 
                    else if(strpos($msg, '[file]') !== false) {
                        // File message
                        preg_match('/\[file\](.*?)\|(.*?)\[\/file\]/', $msg, $matches);
                        $fileName = $matches[1];
                        $filePath = $matches[2];
                        $output .= '<p class="file-message">
                                    <a href="../'.$filePath.'" target="_blank">
                                        <i class="fas fa-file-alt"></i> '.$fileName.'
                                    </a>
                                  </p>';
                    }
                    else {
                        // Regular text/emoji message
                        $output .= '<p>'.htmlspecialchars($msg).'</p>';
                    }
                    
                    $output .= '</div>
                            </div>';
                }else{
                    // Incoming message
                    $output .= '<div class="chat incoming">
                                <img src="../images/'.$row['img'].'" alt="">
                                <div class="details">';
                    
                    if(strpos($msg, '[image]') !== false) {
                        // Image message
                        preg_match('/\[image\](.*?)\[\/image\]/', $msg, $matches);
                        $imagePath = $matches[1];
                        $output .= '<p class="file-message">
                                    <img src="../'.$imagePath.'" alt="Shared Image" class="shared-image">
                                  </p>';
                    } 
                    else if(strpos($msg, '[file]') !== false) {
                        // File message
                        preg_match('/\[file\](.*?)\|(.*?)\[\/file\]/', $msg, $matches);
                        $fileName = $matches[1];
                        $filePath = $matches[2];
                        $output .= '<p class="file-message">
                                    <a href="../'.$filePath.'" target="_blank">
                                        <i class="fas fa-file-alt"></i> '.$fileName.'
                                    </a>
                                  </p>';
                    }
                    else {
                        // Regular text/emoji message
                        $output .= '<p>'.htmlspecialchars($msg).'</p>';
                    }
                    
                    $output .= '</div>
                            </div>';
                }
            }
        }else{
            $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
        }
        echo $output;
    }else{
        header("location: ../login.php");
    }
?>
