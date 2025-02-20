<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

header('Content-Type: application/json');

// Check if an 'id' parameter is passed to fetch a specific member
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Get the ID parameter from the URL

    // Prepare SQL query to fetch a specific member based on the provided ID
    $sql = "SELECT * FROM tb_user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // Bind the ID parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the member is found, return the data
        $row = $result->fetch_assoc();
        echo json_encode(["success" => true, "data" => $row]);
    } else {
        // If no member is found with the given ID
        echo json_encode(["success" => false, "message" => "User not found"]);
    }

    $stmt->close();
} else {
    // If no ID is passed, fetch all members
    $sql = "SELECT * FROM tb_user";
    $result = $conn->query($sql);

    $pelanggan = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $pelanggan[] = $row; // Store all members in an array
        }
        // Return all members data
        echo json_encode(["success" => true, "data" => $pelanggan]);
    } else {
        // If no members are found
        echo json_encode(["success" => false, "message" => "No data found"]);
    }
}

// Close the database connection
$conn->close();
?>
