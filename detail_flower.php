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
    
    // Check if the flower exists
    if (!$flower) {
        echo "Flower not found!";
        exit();
    }
} else {
    echo "No flower ID provided!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flower Details</title>
    <link rel="stylesheet" href="StylesDetail_flower.css"> <!-- Link ke file CSS eksternal -->
</head>
<body>

<div class="container">
    <h2>Detail <?php echo htmlspecialchars($flower['nama_bunga']); ?></h2>
    <div class="flower-detail">
        <img src="<?php echo htmlspecialchars($flower['gambar']); ?>" alt="Flower Image">
        <p><strong>Jenis:</strong> <?php echo htmlspecialchars($flower['jenis_bunga']); ?></p>
        <p><strong>Harga:</strong> <?php echo number_format($flower['harga'], 0, ',', '.'); ?> ribu</p>
        <p><strong>Detail:</strong> <?php echo htmlspecialchars($flower['detail']); ?></p>
    </div>

    <a href="home.php" class="back-button">Back to Flower List</a>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
