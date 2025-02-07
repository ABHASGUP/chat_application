<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    include_once "../config.php";

    header('Content-Type: application/json');
    $response = array('status' => 'error', 'message' => '');

    try {
        if(!isset($_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['password'])){
            throw new Exception("Missing required fields");
        }

        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        if(empty($fname) || empty($lname) || empty($email) || empty($password)){
            throw new Exception("All input fields are required!");
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new Exception("$email is not a valid email!");
        }

        $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
        if(!$sql){
            throw new Exception("Database error: " . mysqli_error($conn));
        }
        
        if(mysqli_num_rows($sql) > 0){
            throw new Exception("$email - This email already exists!");
        }

        if(!isset($_FILES['image'])){
            throw new Exception("No image file uploaded");
        }

        if($_FILES['image']['error'] !== UPLOAD_ERR_OK){
            throw new Exception("Image upload error: " . $_FILES['image']['error']);
        }

        $img_name = $_FILES['image']['name'];
        $img_type = $_FILES['image']['type'];
        $tmp_name = $_FILES['image']['tmp_name'];
        
        if(empty($img_name)){
            throw new Exception("Please select an image file!");
        }

        $img_explode = explode('.', $img_name);
        $img_ext = strtolower(end($img_explode));

        $extensions = ["jpeg", "png", "jpg"];
        if(!in_array($img_ext, $extensions)){
            throw new Exception("Please upload an image file - jpeg, png, jpg");
        }

        $types = ["image/jpeg", "image/jpg", "image/png"];
        if(!in_array($img_type, $types)){
            throw new Exception("Please upload an image file - jpeg, png, jpg");
        }

        $time = time();
        $new_img_name = $time . $img_name;
        
        $upload_dir = "../images";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        if(!is_writable($upload_dir)){
            throw new Exception("Images directory is not writable");
        }
        
        if(!move_uploaded_file($tmp_name, $upload_dir . "/" . $new_img_name)){
            throw new Exception("Failed to upload image!");
        }

        $ran_id = rand(time(), 100000000);
        $status = "Active now";
        $encrypt_pass = md5($password);
        
        $insert_query = "INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                        VALUES ({$ran_id}, '{$fname}', '{$lname}', '{$email}', '{$encrypt_pass}', '{$new_img_name}', '{$status}')";
        
        if(!mysqli_query($conn, $insert_query)){
            throw new Exception("Database insertion error: " . mysqli_error($conn));
        }

        $select_sql2 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
        if(!$select_sql2 || mysqli_num_rows($select_sql2) === 0){
            throw new Exception("Failed to get user data after registration");
        }

        $result = mysqli_fetch_assoc($select_sql2);
        $_SESSION['unique_id'] = $result['unique_id'];
        $response['status'] = 'success';
        $response['message'] = 'success';

    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }

    echo json_encode($response);
?>
