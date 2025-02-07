<?php
    session_start();
    include_once "../config.php";

    $outgoing_id = $_SESSION['unique_id'];
    $searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);

    $sql = "SELECT * FROM users 
            WHERE NOT unique_id = {$outgoing_id} 
            AND (fname LIKE '%{$searchTerm}%' OR lname LIKE '%{$searchTerm}%')";
    $query = mysqli_query($conn, $sql);
    $output = "";
    
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            $output .= '<a href="chat.php?user_id='.$row['unique_id'].'">
                        <div class="content">
                            <img src="php/images/'.$row['img'].'" alt="">
                            <div class="details">
                                <span>'.$row['fname']. " " . $row['lname'].'</span>
                                <p>'.$row['status'].'</p>
                            </div>
                        </div>
                    </a>';
        }
    } else {
        $output .= 'No user found related to your search term';
    }
    echo $output;
?>
