<?php
    session_start();
    if(!isset($_SESSION['unique_id'])){
        header("location: login.php");
    }

    include_once "config.php";

    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = $_POST['incoming_id'];
    $type = $_POST['type'];
    $response = array('status' => 'error');

    if(isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];

        // Get file extension
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Allowed extensions
        $allowedImageExts = array('jpg', 'jpeg', 'png', 'gif');
        $allowedDocExts = array('pdf', 'doc', 'docx', 'txt');

        if($fileError === 0) {
            // Create uploads directory if it doesn't exist
            $uploadDir = "../uploads/";
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generate unique filename
            $fileNameNew = uniqid('', true) . "." . $fileExt;
            $fileDestination = $uploadDir . $fileNameNew;

            // Validate file type and size
            if($type === 'image' && in_array($fileExt, $allowedImageExts) && $fileSize < 5000000) {
                move_uploaded_file($fileTmpName, $fileDestination);
                $message = "[image]uploads/" . $fileNameNew . "[/image]";
                $response['status'] = 'success';
            } else if($type === 'document' && in_array($fileExt, $allowedDocExts) && $fileSize < 10000000) {
                move_uploaded_file($fileTmpName, $fileDestination);
                $message = "[file]" . $fileName . "|uploads/" . $fileNameNew . "[/file]";
                $response['status'] = 'success';
            }

            if($response['status'] === 'success') {
                $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                                        VALUES ({$incoming_id}, {$outgoing_id}, '{$message}')");
                if($sql) {
                    $response['message'] = 'File uploaded successfully';
                }
            }
        }
    }

    echo json_encode($response);
?>
