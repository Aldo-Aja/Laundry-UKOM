<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

header('Content-Type: application/json');

// Function to generate unique invoice code
function generateInvoiceCode() {
    $date = date('ymdhis'); // Current date in YYYYMMDD format
    $randomNumber = rand(100, 999); // Random number between 100-999
    return "INV" . $date . $randomNumber;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"] ?? '';

    if ($action === "add") {
        $id_outlet      = $_POST["id_outlet"];
        $id_member      = $_POST["id_member"];
        $id_paket       = $_POST["id_paket"] ?? null;
        $tgl            = $_POST["tgl"];
        $batas_waktu    = $_POST["batas_waktu"];
        $tgl_bayar      = $_POST["tgl_bayar"] ?? null;
        $biaya_tambahan = floatval($_POST["biaya_tambahan"] ?? 0);
        $diskon         = floatval($_POST["diskon"] ?? 0);
        $status         = $_POST["status"];
        $dibayar        = $_POST["dibayar"];
        $id_user        = $_SESSION['user_id'];
        $kode_invoice   = generateInvoiceCode(); // Generate unique invoice code

        // Get package price
        $stmt_paket = $conn->prepare("SELECT harga FROM tb_paket WHERE id = ?");
        $stmt_paket->bind_param("i", $id_paket);
        $stmt_paket->execute();
        $result_paket = $stmt_paket->get_result();

        if ($row_paket = $result_paket->fetch_assoc()) {
            $harga_paket = floatval($row_paket['harga']);
        } else {
            echo json_encode(["success" => false, "error" => "Paket tidak ditemukan"]);
            exit();
        }
        $stmt_paket->close();

        // Calculate tax and total price
        $tax = 0.0075 * ($harga_paket + $biaya_tambahan);
        $grand_total = ($harga_paket + $biaya_tambahan - $diskon) + $tax;

        // Insert transaction
        $stmt = $conn->prepare("INSERT INTO tb_transaksi (id_outlet, kode_invoice, id_member, tgl, id_paket, batas_waktu, tgl_bayar, biaya_tambahan, diskon, pajak, total_harga, status, dibayar, id_user) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssssdddddssi", $id_outlet, $kode_invoice, $id_member, $tgl, $id_paket, $batas_waktu, $tgl_bayar, $biaya_tambahan, $diskon, $tax, $grand_total, $status, $dibayar, $id_user);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Transaksi berhasil ditambahkan", "invoice" => $kode_invoice, "grand_total" => number_format($grand_total, 2, ',', '.')]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }
        $stmt->close();
    }

    // DELETE TRANSACTION
    if ($action === "delete") {
        $id = $_POST["id"] ?? null;
        if ($id) {
            $stmt = $conn->prepare("DELETE FROM tb_transaksi WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Transaksi berhasil dihapus"]);
            } else {
                echo json_encode(["success" => false, "error" => $conn->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "error" => "ID tidak ditemukan"]);
        }
    }

    // EDIT TRANSACTION
    if ($action === "edit") {
        $id = $_POST["id"] ?? null;
        $id_outlet   = $_POST["id_outlet"];
        $id_member   = $_POST["id_member"];
        $tgl         = $_POST["tgl"];
        $batas_waktu = $_POST["batas_waktu"];
        $tgl_bayar   = $_POST["tgl_bayar"] ?? null;
        $biaya_tambahan = isset($_POST["biaya_tambahan"]) ? floatval($_POST["biaya_tambahan"]) : 0;
        $diskon         = isset($_POST["diskon"]) ? floatval($_POST["diskon"]) : 0;
        $status         = $_POST["status"];
        $dibayar        = $_POST["dibayar"];
        $id_paket       = $_POST["id_paket"] ?? null;

        if ($id) {
            // Retrieve package price for the given id_paket
            $stmt_paket = $conn->prepare("SELECT harga FROM tb_paket WHERE id = ?");
            $stmt_paket->bind_param("i", $id_paket);
            $stmt_paket->execute();
            $result_paket = $stmt_paket->get_result();
            if ($row_paket = $result_paket->fetch_assoc()) {
                $harga_paket = floatval($row_paket['harga']);
            } else {
                echo json_encode(["success" => false, "error" => "Paket tidak ditemukan"]);
                exit();
            }
            $stmt_paket->close();

            // Calculate tax and total price
            $tax = 0.0075 * ($harga_paket + $biaya_tambahan);
            $grand_total = ($harga_paket + $biaya_tambahan - $diskon) + $tax;
            $pajak = $tax;

            // Update transaction
            $stmt = $conn->prepare("UPDATE tb_transaksi SET id_outlet = ?, id_member = ?, tgl = ?, batas_waktu = ?, tgl_bayar = ?, biaya_tambahan = ?, diskon = ?, pajak = ?, status = ?, dibayar = ?, id_paket = ?, total_harga = ? WHERE id = ?");
            $stmt->bind_param("iisssdddssidi", $id_outlet, $id_member, $tgl, $batas_waktu, $tgl_bayar, $biaya_tambahan, $diskon, $pajak, $status, $dibayar, $id_paket, $grand_total, $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Transaksi berhasil diperbarui", "grand_total" => $grand_total]);
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
