<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"] ?? '';

    // Add a new User
    if ($action === "add") {
        $id_outlet = $_POST["id_outlet"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $nama = $_POST["nama"];
        $role = $_POST["role"];

        $checkStmt = $conn->prepare("SELECT id FROM tb_user WHERE username = ?");
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $resultCheck = $checkStmt->get_result();

        if ($resultCheck->num_rows > 0) {
            echo json_encode([
                "success" => false,
                "error"   => "User dengan username tersebut sudah ada."
            ]);
            exit();
        }
        $checkStmt->close();

        $stmt = $conn->prepare("INSERT INTO tb_user (id_outlet, username, password, nama, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id_outlet, $username, $password, $nama, $role);
 
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "User berhasil ditambahkan"]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }

        $stmt->close();
    }

    // Delete a pelanggan
    if ($action === "delete") {
        $id = $_POST["id"] ?? null;

        if ($id) {
            $stmt = $conn->prepare("DELETE FROM tb_user WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "User berhasil dihapus"]);
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
        $id_outlet = $_POST['id_outlet'];
        $nama = $_POST["nama"];
        $username = $_POST["username"];
        $password = $_POST["password"];

        if ($id) {
            $stmt = $conn->prepare("UPDATE tb_user SET id_outlet = ?, nama = ?, username = ?, password = ? WHERE id = ?");
            $stmt->bind_param("isssi", $id_outlet, $nama, $username, $password, $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "User berhasil diperbarui"]);
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
