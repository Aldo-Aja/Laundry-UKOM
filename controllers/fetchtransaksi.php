<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

header('Content-Type: application/json');

// If an 'id' parameter is passed, fetch a specific transaction with package details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT t.*, p.nama_paket AS paket_nama, p.harga AS paket_harga
            FROM tb_transaksi t 
            LEFT JOIN tb_paket p ON t.id_paket = p.id
            WHERE t.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $transaksi = $result->fetch_assoc();
        echo json_encode(["success" => true, "data" => $transaksi]);
    } else {
        echo json_encode(["success" => false, "message" => "Transaksi tidak ditemukan"]);
    }
    $stmt->close();
} else {
    // If no 'id' parameter, fetch all transactions with package details
    $sql = "SELECT t.*, p.nama_paket AS paket_nama, p.harga AS paket_harga
            FROM tb_transaksi t 
            LEFT JOIN tb_paket p ON t.id_paket = p.id";
    $result = $conn->query($sql);

    $transaksi = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $transaksi[] = $row;
        }
        echo json_encode(["success" => true, "data" => $transaksi]);
    } else {
        echo json_encode(["success" => false, "message" => "Data transaksi tidak ditemukan"]);
    }
}

$conn->close();
?>
