<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

header('Content-Type: application/json');

function generateInvoiceCode() {
    return "INV" . date('ymdhis') . rand(100, 999);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"] ?? '';

    // Valid ENUM values
    $validStatus = ["baru", "proses", "selesai", "diambil"];
    $validDibayar = ["dibayar", "belum_dibayar"];

    if ($action === "add") {
        $id_outlet = $_POST["id_outlet"];
        $id_member = $_POST["id_member"];
        $id_paket = $_POST["id_paket"] ?? null;
        $jumlah_qty = $_POST["jumlah_qty"] ?? 1;
        $tgl = $_POST["tgl"];
        $batas_waktu = $_POST["batas_waktu"];
        $tgl_bayar = $_POST["tgl_bayar"] ?? null;
        $biaya_tambahan = floatval($_POST["biaya_tambahan"] ?? 0);
        $diskon = floatval($_POST["diskon"] ?? 0);
        $status = $_POST["status"] ?? "baru";
        $dibayar = $_POST["dibayar"] ?? "belum_dibayar";
        $id_user = $_SESSION['user_id'] ?? 0;
        $kode_invoice = generateInvoiceCode();

        // Pastikan status valid
        if (!in_array($status, $validStatus)) $status = "baru";
        if (!in_array($dibayar, $validDibayar)) $dibayar = "belum_dibayar";

        // Ambil harga paket
        $stmt_paket = $conn->prepare("SELECT harga FROM tb_paket WHERE id = ?");
        $stmt_paket->bind_param("i", $id_paket);
        $stmt_paket->execute();
        $result_paket = $stmt_paket->get_result();
        $stmt_paket->close();

        if ($row_paket = $result_paket->fetch_assoc()) {
            $harga_paket = floatval($row_paket['harga']);
        } else {
            echo json_encode(["success" => false, "error" => "Paket tidak ditemukan"]);
            exit();
        }

        // Hitung harga total
        $harga_akhir_paket = $harga_paket * $jumlah_qty;
        $pajak = 0.0075 * ($harga_akhir_paket + $biaya_tambahan);
        $diskon_persen = ($diskon / 100) * ($harga_paket + $biaya_tambahan); // Diskon dalam bentuk nilai
        $grand_total = ($harga_akhir_paket + $biaya_tambahan - $diskon_persen) + $pajak;


        // Insert transaksi
        $stmt = $conn->prepare("INSERT INTO tb_transaksi (id_outlet, kode_invoice, id_member, id_paket, jumlah_qty, tgl, batas_waktu, tgl_bayar, biaya_tambahan, diskon, pajak, total_harga, status, dibayar, id_user) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isiiisssdddsssi", $id_outlet, $kode_invoice, $id_member, $id_paket, $jumlah_qty, $tgl, $batas_waktu, $tgl_bayar, $biaya_tambahan, $diskon, $pajak, $grand_total, $status, $dibayar, $id_user);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Transaksi berhasil ditambahkan", "invoice" => $kode_invoice]);
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
        $id_outlet = $_POST["id_outlet"];
        $id_member = $_POST["id_member"];
        $tgl = $_POST["tgl"];
        $batas_waktu = $_POST["batas_waktu"];
        $tgl_bayar = $_POST["tgl_bayar"] ?? null;
        $biaya_tambahan = floatval($_POST["biaya_tambahan"] ?? 0);
        $diskon = floatval($_POST["diskon"] ?? 0);
        $status = $_POST["status"] ?? "baru";
        $dibayar = $_POST["dibayar"] ?? "belum_dibayar";
        $id_paket = $_POST["id_paket"] ?? null;
        $jumlah_qty = $_POST["jumlah_qty"] ?? 1;

        // Pastikan status valid
        if (!in_array($status, $validStatus)) $status = "baru";
        if (!in_array($dibayar, $validDibayar)) $dibayar = "belum_dibayar";

        if ($id) {
            // Ambil harga paket
            $stmt_paket = $conn->prepare("SELECT harga FROM tb_paket WHERE id = ?");
            $stmt_paket->bind_param("i", $id_paket);
            $stmt_paket->execute();
            $result_paket = $stmt_paket->get_result();
            $stmt_paket->close();

            if ($row_paket = $result_paket->fetch_assoc()) {
                $harga_paket = floatval($row_paket['harga']);
            } else {
                echo json_encode(["success" => false, "error" => "Paket tidak ditemukan"]);
                exit();
            }

            // Hitung total harga
            $harga_akhir_paket = $harga_paket * $jumlah_qty;
            $pajak = 0.0075 * ($harga_akhir_paket + $biaya_tambahan);
            $diskon_persen = ($diskon / 100) * ($harga_akhir_paket + $biaya_tambahan); // Diskon dalam bentuk nilai
            $grand_total = ($harga_akhir_paket + $biaya_tambahan - $diskon_persen) + $pajak;


            // Update transaksi
            $stmt = $conn->prepare("UPDATE tb_transaksi SET id_outlet = ?, id_member = ?, tgl = ?, batas_waktu = ?, tgl_bayar = ?, biaya_tambahan = ?, diskon = ?, pajak = ?, status = ?, dibayar = ?, id_paket = ?, jumlah_qty = ?, total_harga = ? WHERE id = ?");
            $stmt->bind_param("iisssdddssiidi", $id_outlet, $id_member, $tgl, $batas_waktu, $tgl_bayar, $biaya_tambahan, $diskon, $pajak, $status, $dibayar, $id_paket, $jumlah_qty, $grand_total, $id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Transaksi berhasil diperbarui"]);
            } else {
                echo json_encode(["success" => false, "error" => $conn->error]);
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>
