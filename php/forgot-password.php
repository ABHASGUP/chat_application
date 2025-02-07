<?php
session_start();
include_once "../config.php";

header('Content-Type: application/json');
$response = array('status' => 'error', 'message' => '');

try {
    if(!isset($_POST['email'])) {
        throw new Exception("Email is required");
    }

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
    if(mysqli_num_rows($sql) == 0) {
        throw new Exception("No account found with this email address");
    }

    // Generate unique token
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Save token in database
    $update_query = "UPDATE users SET reset_token = '{$token}', reset_token_expiry = '{$expiry}' 
                     WHERE email = '{$email}'";
    if(!mysqli_query($conn, $update_query)) {
        throw new Exception("Error updating reset token");
    }

    // Create reset link
    $reset_link = "http://{$_SERVER['HTTP_HOST']}/chat_application/reset-password.php?token=" . $token;

    // In a production environment, you would send this via email
    // For this demo, we'll just return the link
    $response['status'] = 'success';
    $response['message'] = 'Password reset link has been generated. Click here: <a href="' . $reset_link . '">Reset Password</a>';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
