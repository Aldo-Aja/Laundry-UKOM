<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"] ?? '';

    // Tambah data paket
    if ($action === "add") {
        $id_outlet = $_POST["id_outlet"];
        $jenis = $_POST["jenis"];
        $nama_paket = $_POST["nama_paket"];
        $harga = $_POST["harga"];

        // Periksa apakah semua field sudah diisi
        if (empty($id_outlet) || empty($jenis) || empty($nama_paket) || empty($harga)) {
            echo json_encode(["success" => false, "error" => "Semua field harus diisi"]);
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO tb_paket (id_outlet, jenis, nama_paket, harga) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $id_outlet, $jenis, $nama_paket, $harga);
 
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Paket berhasil ditambahkan"]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }

        $stmt->close();
    }

    // Hapus data paket
    if ($action === "delete") {
        $id = $_POST["id"] ?? null;

        if ($id) {
            $stmt = $conn->prepare("DELETE FROM tb_paket WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Paket berhasil dihapus"]);
            } else {
                echo json_encode(["success" => false, "error" => $conn->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(["success" => false, "error" => "ID tidak ditemukan"]);
        }
    }

    // Edit data paket
    if ($action === "edit") {
        $id = $_POST["id"] ?? null;
        $id_outlet = $_POST["id_outlet"];
        $jenis = $_POST["jenis"];
        $nama_paket = $_POST["nama_paket"];
        $harga = $_POST["harga"];
    
        // Check if ID is present
        if (!$id) {
            echo json_encode(["success" => false, "error" => "ID paket tidak ditemukan"]);
            exit();
        }
    
        // Check if any field is empty
        if (empty($id_outlet) || empty($jenis) || empty($nama_paket) || empty($harga)) {
            echo json_encode(["success" => false, "error" => "Semua field harus diisi"]);
            exit();
        }
    
        // Sanitize/validate harga as numeric
        if (!is_numeric($harga)) {
            echo json_encode(["success" => false, "error" => "Harga harus berupa angka"]);
            exit();
        }
    
        $stmt = $conn->prepare("UPDATE tb_paket SET id_outlet = ?, jenis = ?, nama_paket = ?, harga = ? WHERE id = ?");
        $stmt->bind_param("issii", $id_outlet, $jenis, $nama_paket, $harga, $id);
    
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Paket berhasil diperbarui"]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
    
        $stmt->close();
    }
    
}
$conn->close();
?>
