<?php 
include 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get the admin's username from the session
$admin_name = $_SESSION['username'];

// Query to fetch flowers from the database
$sql = "SELECT * FROM bunga";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home - Flower Shop</title>
    <link rel="stylesheet" href="stylesHome.css"> <!-- Link to external CSS -->
</head>
<body>

<!-- Logout button placed outside the container -->
<a href="logout.php" class="logout-button">Logout</a>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($admin_name); ?>!</h2>
    <p class="welcome-message">Use the button below to manage flowers or see the flowers you've added.</p>

    <!-- Button to go to Add Flowers Page -->
    <div class="button-container">
        <a href="list_flower.php" class="btn">Add Flowers</a>
    </div>

    <!-- Display flowers added by the admin -->
    <div class="flower-list">
        <?php
        if ($result->num_rows > 0) {
            // Loop through and display each flower
            while ($row = $result->fetch_assoc()) {
                echo "<div class='flower-item'>";
                echo "<img src='" . htmlspecialchars($row['gambar']) . "' alt='" . htmlspecialchars($row['nama_bunga']) . "'>";
                echo "<h3>" . htmlspecialchars($row['nama_bunga']) . "</h3>";

                // Buttons for Detail, Edit, and Delete with URL encoding
                echo "<div class='buttons'>";
                echo "<a href='detail_flower.php?id=" . urlencode($row['id']) . "' class='btn detail'>Detail</a>";
                echo "<a href='edit_flower.php?id=" . urlencode($row['id']) . "' class='btn edit'>Edit</a>";
                echo "<a href='delete_flower.php?id=" . urlencode($row['id']) . "' class='btn delete'>Delete</a>";
                echo "</div>"; // buttons div
                echo "</div>"; // flower-item div
            }
        } else {
            echo "<p>No flowers found.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>

<?php
$conn->close();

include 'footer.php';
?>
