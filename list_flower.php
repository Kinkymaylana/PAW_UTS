<?php
include 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_bunga = $_POST['nama_bunga'];
    $jenis_bunga = $_POST['jenis_bunga'];
    $harga = $_POST['harga'];
    $detail = $_POST['detail'];
    
    // Handle file upload
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // Create the folder if it doesn't exist
    }
    $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the image file is a real image
    $check = getimagesize($_FILES["gambar"]["tmp_name"]);
    if ($check === false) {
        $error = "File is not an image.";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if ($uploadOk && !in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
        $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Attempt to upload the file
    if ($uploadOk && move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
        // Insert flower data into the database
        $sql = "INSERT INTO bunga (nama_bunga, jenis_bunga, gambar, harga, detail) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nama_bunga, $jenis_bunga, $target_file, $harga, $detail);

        if ($stmt->execute()) {
            $success = "Bunga berhasil ditambahkan.";
        } else {
            $error = "Terjadi kesalahan saat menambahkan bunga.";
        }

        $stmt->close();
    } else {
        $error = "Gagal mengunggah gambar.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Bunga - Admin</title>
    <link rel="stylesheet" href="stylesList_flower.css"> <!-- Link to the new CSS file -->
</head>
<body>

<div class="container">
    <h2>Add New Flower</h2>

    <?php if (isset($error)): ?>
        <div class="error-message"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="success-message"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label for="nama_bunga">Nama Bunga</label>
        <input type="text" id="nama_bunga" name="nama_bunga" required>

        <label for="jenis_bunga">Jenis Bunga</label>
        <input type="text" id="jenis_bunga" name="jenis_bunga" required>

        <label for="harga">Harga</label>
        <input type="number" id="harga" name="harga" required>

        <label for="gambar">Gambar Bunga</label>
        <input type="file" id="gambar" name="gambar" required>

        <label for="detail">Detail Bunga</label>
        <textarea id="detail" name="detail" rows="5" required></textarea>

        <input type="submit" value="Add Flower">
    </form>

    <!-- Back to Home Button -->
    <div class="back-button">
        <a href="home.php">Back to Home</a>
    </div>

</div>

</body>
</html>
