<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Chat App</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
</head>
<body>
    <div class="wrapper">
        <section class="form forgot-password">
            <header>Reset Password</header>
            <form action="#" method="POST" autocomplete="off">
                <div class="error-text"></div>
                <div class="field input">
                    <label>Email Address</label>
                    <input type="text" name="email" placeholder="Enter your email" required>
                </div>
                <div class="field button">
                    <input type="submit" name="submit" value="Send Reset Link">
                </div>
                <div class="link">Remember your password? <a href="login.php">Login now</a></div>
            </form>
        </section>
    </div>
    <script src="javascript/forgot-password.js"></script>
</body>
</html>
