<?php
include 'config.php';

// Proses login jika form dikirim melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Menggunakan prepared statement untuk menghindari SQL injection
    $sql = $conn->prepare("SELECT * FROM users WHERE username=?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();

    // Jika username ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Set session dengan username dan redirect ke index.php
            session_start();
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }

    // Menutup prepared statement dan koneksi
    $sql->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Flower Shop</title>
    <link rel="stylesheet" href="stylesLogin.css"> <!-- Link to the new CSS file -->
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter your username" required>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password" required>
                <input type="submit" value="Login">
            </form>
            <div class="register-wrapper">
                <span>Not a member yet?</span>
                <a href="register.php" class="register-button">Register now</a>
            </div>
        </div>
        <div class="right-side">
            <h2>Welcome to Flower Shop!</h2>
            <p>
                Explore our wide selection of fresh and beautiful flowers perfect for any occasion. 
                Login to access your account and enjoy a seamless shopping experience.
            </p>
        </div>
    </div>
</body>
</html>
