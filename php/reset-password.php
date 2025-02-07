<?php
session_start();
include_once "../config.php";

header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => '');

try {
    if(!isset($_POST['token'], $_POST['password'], $_POST['confirm_password'])) {
        throw new Exception("All fields are required");
    }

    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if($password !== $confirm_password) {
        throw new Exception("Passwords do not match");
    }

    if(strlen($password) < 6) {
        throw new Exception("Password must be at least 6 characters long");
    }

    // Check if token exists and is not expired
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE reset_token = '{$token}' 
                               AND reset_token_expiry > NOW()");
    
    if(mysqli_num_rows($sql) == 0) {
        throw new Exception("Invalid or expired reset token");
    }

    $encrypt_pass = md5($password);

    // Update password and clear reset token
    $update_query = "UPDATE users SET password = '{$encrypt_pass}', 
                    reset_token = NULL, reset_token_expiry = NULL 
                    WHERE reset_token = '{$token}'";
    
    if(!mysqli_query($conn, $update_query)) {
        throw new Exception("Error updating password");
    }

    $response['status'] = 'success';
    $response['message'] = 'Password has been reset successfully. You can now login with your new password.';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
