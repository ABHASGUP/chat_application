<?php 
    session_start();
    include_once "config.php";
    if(!isset($_SESSION['unique_id'])){
        header("location: login.php");
    }
    if(!isset($_GET['user_id'])) {
        header("location: users.php");
    }
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");
    if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_assoc($sql);
    }else{
        header("location: users.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://emoji-css.afeld.me/emoji.css" rel="stylesheet">
    <script src="https://twemoji.maxcdn.com/v/latest/twemoji.min.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrapper">
        <section class="chat-area">
            <header>
                <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                <img src="images/<?php echo $row['img']; ?>" alt="">
                <div class="details">
                    <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
                    <p><?php echo $row['status']; ?></p>
                </div>
            </header>
            <div class="chat-box">
                <!-- Chat messages will be loaded here -->
            </div>
            <form action="#" class="typing-area" autocomplete="off">
                <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
                <div class="emoji-container">
                    <button type="button" class="emoji-btn" title="Emoji"><i class="far fa-smile"></i></button>
                    <div class="emoji-picker">
                        <div class="emoji-wrapper">
                            <div class="emoji-categories">
                                <span class="active" data-category="smileys">😊</span>
                                <span data-category="people">👋</span>
                                <span data-category="animals">🐶</span>
                                <span data-category="food">🍎</span>
                                <span data-category="activities">⚽</span>
                                <span data-category="places">🚗</span>
                                <span data-category="symbols">❤️</span>
                            </div>
                            <div class="emoji-list">
                                <!-- Emojis will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="attachment-btn" title="Attach File"><i class="fas fa-paperclip"></i></button>
                <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
                <button type="submit"><i class="fas fa-paper-plane"></i></button>
                
                <div class="attachment-menu">
                    <button type="button" class="upload-btn" data-type="photo">
                        <i class="fas fa-image"></i> Photo/Video
                    </button>
                    <button type="button" class="upload-btn" data-type="file">
                        <i class="fas fa-file"></i> Document
                    </button>
                    <button type="button" class="upload-btn" data-type="location">
                        <i class="fas fa-map-marker-alt"></i> Location
                    </button>
                </div>
            </form>
            <input type="file" id="file-input" class="file-input" hidden>
        </section>
    </div>
    <script src="javascript/chat.js"></script>
</body>
</html>
