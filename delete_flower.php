<?php
include 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if the flower ID is passed
if (isset($_GET['id'])) {
    $flower_id = $_GET['id'];

    // Delete the flower from the database
    $sql = "DELETE FROM bunga WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $flower_id);

    if ($stmt->execute()) {
        // Redirect back to home page after successful deletion
        header("Location: home.php");
    } else {
        echo "Error deleting flower.";
    }

    $stmt->close();
}

$conn->close();
?>
