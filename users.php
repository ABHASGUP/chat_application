<?php 
    session_start();
    include_once "config.php";
    if(!isset($_SESSION['unique_id'])){
        header("location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realtime Chat App</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
</head>
<body>
    <div class="wrapper">
        <section class="users">
            <header>
                <?php 
                    $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                    if(mysqli_num_rows($sql) > 0){
                        $row = mysqli_fetch_assoc($sql);
                    } else {
                        header("location: login.php");
                        exit;
                    }
                ?>
                <div class="content">
                    <img src="/chat_application/images/<?php echo htmlspecialchars($row['img']); ?>" alt="">
                    <div class="details">
                        <span><?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></span>
                        <p><?php echo htmlspecialchars($row['status']); ?></p>
                    </div>
                </div>
                <a href="php/logout.php" class="logout">Logout</a>
            </header>
            <div class="search">
                <span class="text">Select a user to start chat</span>
                <input type="text" placeholder="Enter name to search...">
                <button><i class="fas fa-search"></i></button>
            </div>
            <div class="users-list">
            </div>
        </section>
    </div>
    <script src="javascript/users.js"></script>
</body>
</html>
