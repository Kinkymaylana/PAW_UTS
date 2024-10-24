<?php
include 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if flower ID is provided
if (isset($_GET['id'])) {
    $flower_id = $_GET['id'];

    // Query to get flower details from database
    $sql = "SELECT * FROM bunga WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $flower_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $flower = $result->fetch_assoc();
    
    if (!$flower) {
        echo "Flower not found!";
        exit();
    }
} else {
    echo "No flower ID provided!";
    exit();
}

// Update flower details in database
if (isset($_POST['update'])) {
    $name = $_POST['nama_bunga'];
    $type = $_POST['jenis_bunga'];
    $details = $_POST['detail'];

    $sql = "UPDATE bunga SET nama_bunga=?, jenis_bunga=?, detail=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $type, $details, $flower_id);

    if ($stmt->execute()) {
        header("Location: home.php");
        exit();
    } else {
        echo "Error updating flower.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Flower</title>
    <link rel="stylesheet" href="stylesEdit_flower.css"> <!-- Link to the new CSS file -->
</head>
<body>

<div class="container">
    <h2>Edit Flower</h2>

    <form method="post">
        <div class="form-group">
            <label for="nama_bunga">Nama Bunga</label>
            <input type="text" name="nama_bunga" id="nama_bunga" value="<?php echo htmlspecialchars($flower['nama_bunga']); ?>" required>
        </div>

        <div class="form-group">
            <label for="jenis_bunga">Jenis Bunga</label>
            <input type="text" name="jenis_bunga" id="jenis_bunga" value="<?php echo htmlspecialchars($flower['jenis_bunga']); ?>" required>
        </div>

        <div class="form-group">
            <label for="detail">Detail Bunga</label>
            <textarea name="detail" id="detail" required><?php echo htmlspecialchars($flower['detail']); ?></textarea>
        </div>

        <button type="submit" name="update" class="btn-submit">Update Flower</button>
    </form>

    <a href="home.php" class="back-button">Back to Flower List</a>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
