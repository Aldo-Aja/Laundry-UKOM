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
        $tlp = $_POST["tlp"];

        $checkStmt = $conn->prepare("SELECT id FROM tb_outlet WHERE nama = AND alamat = ? AND tlp = ?");
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $resultCheck = $checkStmt->get_result();

        if ($resultCheck->num_rows > 0) {
            echo json_encode([
                "success" => false,
                "error"   => "Outlet dengan Nama tersebut sudah ada."
            ]);
            exit();
        }
        $checkStmt->close();

        $stmt = $conn->prepare("INSERT INTO tb_outlet (nama, alamat, tlp) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $alamat, $tlp);
 
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Outlet berhasil ditambahkan"]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }

        $stmt->close();
    }

    // Delete a pelanggan
    if ($action === "delete") {
        $id = $_POST["id"] ?? null;

        if ($id) {
            $stmt = $conn->prepare("DELETE FROM tb_outlet WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Outlet berhasil dihapus"]);
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
        $tlp = $_POST["tlp"];

        if ($id) {
            $stmt = $conn->prepare("UPDATE tb_outlet SET nama = ?, alamat = ?, tlp = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nama, $alamat, $tlp, $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Outlet berhasil diperbarui"]);
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
