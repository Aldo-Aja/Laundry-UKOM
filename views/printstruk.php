<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

// Pastikan parameter ID transaksi tersedia
if (!isset($_GET['id'])) {
    echo "No transaction ID provided.";
    exit();
}

$id = $_GET['id'];

// Ambil data transaksi beserta nama member dari tabel tb_transaksi
$sql = "SELECT t.*, m.nama AS nama_member 
        FROM tb_transaksi t 
        JOIN tb_member m ON t.id_member = m.id 
        WHERE t.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Transaction not found.";
    exit();
}

$transaksi = $result->fetch_assoc();
$stmt->close();

// Query untuk mendapatkan nama paket dan harga satuan dari tb_paket berdasarkan id_paket
$nama_paket = "N/A";
$harga_satuan = 0;
if (!empty($transaksi['id_paket'])) {
    $stmt_paket = $conn->prepare("SELECT nama_paket, harga FROM tb_paket WHERE id = ?");
    $stmt_paket->bind_param("i", $transaksi['id_paket']);
    $stmt_paket->execute();
    $result_paket = $stmt_paket->get_result();
    if ($result_paket->num_rows > 0) {
        $row_paket = $result_paket->fetch_assoc();
        $nama_paket = $row_paket['nama_paket'];
        $harga_satuan = $row_paket['harga'];
    }
    $stmt_paket->close();
}

$conn->close();

// Fungsi untuk format rupiah
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Ambil field dari transaksi
$qty = isset($transaksi['jumlah_qty']) ? (int)$transaksi['jumlah_qty'] : 0;
$biaya_tambahan = isset($transaksi['biaya_tambahan']) ? (int)$transaksi['biaya_tambahan'] : 0;
$diskon = isset($transaksi['diskon']) ? (float)$transaksi['diskon'] : 0;
$pajak = isset($transaksi['pajak']) ? (float)$transaksi['pajak'] : 0;

// **Hitung Total Harga Paket**
$harga_total_paket = $harga_satuan * $qty;

// **Hitung Diskon (jika ada, dalam persen)**
$diskon_nominal = ($diskon / 100) * ($harga_total_paket + $biaya_tambahan);

// **Hitung Pajak (0.75%) dengan benar**
$pajak_nominal = 0.0075 * ($harga_total_paket + $biaya_tambahan);

// **Hitung Total Keseluruhan**
$total_harga = ($harga_total_paket + $biaya_tambahan - $diskon_nominal) + $pajak_nominal;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice - Washify</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
      .invoice-box {
          padding: 20px;
          border: 1px solid #ddd;
          max-width: 800px;
          margin: 20px auto;
          background: #fff;
      }
      .text-end {
          text-align: right;
      }
  </style>
</head>
<body>
<div class="container">
  <div class="invoice-box">
    <h2 class="text-center">Invoice</h2>
    <hr>
    <table class="table">
      <tr>
        <td><strong>Invoice:</strong> <?php echo htmlspecialchars($transaksi['kode_invoice']); ?></td>
        <td class="text-end"><strong>Tanggal:</strong> <?php echo htmlspecialchars($transaksi['tgl']); ?></td>
      </tr>
      <tr>
        <td><strong>Nama Pelanggan:</strong> <?php echo htmlspecialchars($transaksi['nama_member']); ?></td>
      </tr>
    </table>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th class="text-center" style="width: 5%">#</th>
          <th>Paket</th>
          <th class="text-center" style="width: 10%">QTY</th>
          <th class="text-end" style="width: 20%">Harga Satuan</th>
          <th class="text-end" style="width: 20%">Total</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="text-center">1</td>
          <td><?php echo htmlspecialchars($nama_paket); ?></td>
          <td class="text-center"><?php echo $qty; ?></td>
          <td class="text-end"><?php echo formatRupiah($harga_satuan); ?></td>
          <td class="text-end"><?php echo formatRupiah($harga_total_paket); ?></td>
        </tr>
        <tr>
          <td colspan="4" class="strong text-end">Biaya Tambahan</td>
          <td class="text-end"><?php echo formatRupiah($biaya_tambahan); ?></td>
        </tr>
        <tr>
          <td colspan="4" class="strong text-end">Diskon (<?php echo $diskon; ?>%)</td>
          <td class="text-end">-<?php echo formatRupiah($diskon_nominal); ?></td>
        </tr>
        <tr>
          <td colspan="4" class="strong text-end">Pajak (<?php echo 0.75 ?>%)</td>
          <td class="text-end"><?php echo formatRupiah($pajak_nominal); ?></td>
        </tr>
        <tr>
          <td colspan="4" class="font-weight-bold text-uppercase text-end">Total Keseluruhan</td>
          <td class="font-weight-bold text-end"><?php echo formatRupiah($total_harga); ?></td>
        </tr>
      </tbody>
    </table>
    <p class="text-center mt-4">Terima kasih telah menggunakan layanan Washify!</p>
  </div>
</div>
</body>
</html>
