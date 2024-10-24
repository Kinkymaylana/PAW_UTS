<?php
include 'config.php';

// Proses register jika form dikirim melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak sesuai!";
    } else {
        // Menggunakan prepared statement untuk menghindari SQL injection
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $sql->bind_param("ss", $username, $hashed_password);

        if ($sql->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Terjadi kesalahan saat mendaftar. Coba lagi.";
        }

        // Menutup prepared statement dan koneksi
        $sql->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Flower Shop</title>
    <link rel="stylesheet" href="stylesRegister.css"> <!-- Link ke file CSS terpisah -->
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Enter your username" required>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
            <input type="submit" value="Register">
        </form>
        <div class="login-wrapper">
            <span>Already have an account?</span>
            <a href="login.php" class="login-button">Login here</a>
        </div>
    </div>
</body>
</html>
