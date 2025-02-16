<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    // Jika parameter 'id' ada, ambil data paket berdasarkan ID tersebut
    $id = $_GET['id'];
    $sql = "SELECT * FROM tb_paket WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $paket = $result->fetch_assoc();
        echo json_encode(["success" => true, "data" => $paket]);
    } else {
        echo json_encode(["success" => false, "message" => "Paket tidak ditemukan"]);
    }
    $stmt->close();
} else {
    // Jika tidak ada parameter 'id', ambil semua data paket
    $sql = "SELECT * FROM tb_paket";
    $result = $conn->query($sql);

    $paket = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $paket[] = $row;
        }
        echo json_encode(["success" => true, "data" => $paket]);
    } else {
        echo json_encode(["success" => false, "message" => "Data tidak ditemukan"]);
    }
}

$conn->close();
?>
