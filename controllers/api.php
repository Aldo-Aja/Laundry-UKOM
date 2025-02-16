<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"] ?? '';

    // Add a new pelanggan
    if ($action === "add") {
        $nama = $_POST["nama"];
        $alamat = $_POST["alamat"];
        $jenis_kelamin = $_POST["jenis_kelamin"];
        $tlp = $_POST["tlp"];

        $stmt = $conn->prepare("INSERT INTO tb_member (nama, alamat, jenis_kelamin, tlp) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $alamat, $jenis_kelamin, $tlp);
 
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Pelanggan berhasil ditambahkan"]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }

        $stmt->close();
    }

    // Delete a pelanggan
    if ($action === "delete") {
        $id = $_POST["id"] ?? null;

        if ($id) {
            $stmt = $conn->prepare("DELETE FROM tb_member WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Pelanggan berhasil dihapus"]);
            } else {
                echo json_encode(["success" => false, "error" => $conn->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(["success" => false, "error" => "ID tidak ditemukan"]);
        }
    }

    // Edit a pelanggan
    if ($action === "edit") {
        $id = $_POST["id"] ?? null;
        $nama = $_POST["nama"];
        $alamat = $_POST["alamat"];
        $jenis_kelamin = $_POST["jenis_kelamin"];
        $tlp = $_POST["tlp"];

        if ($id) {
            $stmt = $conn->prepare("UPDATE tb_member SET nama = ?, alamat = ?, jenis_kelamin = ?, tlp = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $nama, $alamat, $jenis_kelamin, $tlp, $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Pelanggan berhasil diperbarui"]);
            } else {
                echo json_encode(["success" => false, "error" => $conn->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(["success" => false, "error" => "ID tidak ditemukan"]);
        }
    }
}
$conn->close();
?>
