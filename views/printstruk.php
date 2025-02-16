<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

// Pastikan parameter ID transaksi tersedia
if (!isset($_GET['id'])) {
    echo "No transaction ID provided.";
    exit();
}

$id = $_GET['id'];

// Ambil data transaksi dan nama member dari database
$sql = "SELECT tb_transaksi.*, tb_member.nama AS nama_member FROM tb_transaksi 
        JOIN tb_member ON tb_transaksi.id_member = tb_member.id 
        WHERE tb_transaksi.id = ?";
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
$conn->close();
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>
<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta20
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Invoice</title>
    <!-- CSS files -->
    <link href="../assets/css/tabler.min.css?1692870487" rel="stylesheet"/>
    <link href="../assets/css/tabler-flags.min.css?1692870487" rel="stylesheet"/>
    <link href="../assets/css/tabler-payments.min.css?1692870487" rel="stylesheet"/>
    <link href="../assets/css/tabler-vendors.min.css?1692870487" rel="stylesheet"/>
    <link href="../assets/css/demo.min.css?1692870487" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
  </head>
  <body >
    <script src="./dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page">
      <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title">
                  Invoice
                </h2>
              </div>
              <!-- Page title actions -->
              <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary" onclick="javascript:window.print();">
                  <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                  Print Invoice
                </button>
              </div>
            </div>
          </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
          <div class="container-xl">
            <div class="card card-lg">
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <p class="h3">Washify</p>
                  </div>
                  <div class="col-6 text-end">
                    <p class="h3"><?php echo $transaksi['nama_member'] ?></p>
                    <address>
                      <?php
                      ?>
                    </address>
                  </div>
                  <div class="col-12 my-5">
                    <h1>Invoice <?php echo $transaksi['kode_invoice']?></h1>
                  </div>
                </div>
                <table class="table table-transparent table-responsive">
                  <thead>
                    <tr>
                      <th class="text-center" style="width: 1%"></th>
                      <th>Jenis Paket</th>
                      <th class="text-center" style="width: 1%">Berat</th>
                      <th class="text-end" style="width: 1%">Harga</th>
                    </tr>
                  </thead>
                  <tr>
                    <td class="text-center">1</td>
                    <td>
                      <p class="strong mb-1"></p>
                    </td>
                    <td class="text-center">
                      1
                    </td>
                    <td class="text-end">$1.800,00</td>
                  </tr>
                  <tr>
                    <td colspan="3" class="strong text-end">Total</td>
                    <td class="text-end"><?php ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="strong text-end">Pajak</td>
                    <td class="text-end">0,75%</td>
                  </tr>
                  <tr>
                    <td colspan="3" class="strong text-end">Diskon</td>
                    <td class="text-end"><?php echo $transaksi['diskon']?></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="font-weight-bold text-uppercase text-end">Total Keseluruhan</td>
                    <td class="font-weight-bold text-end"><?php echo formatRupiah($transaksi['total_harga']);?></td>
                  </tr>
                </table>
                <p class="text-secondary text-center mt-5">Thank you very much for doing business with us. We look forward to working with
                  you again!</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="../assets/js/tabler.min.js?1692870487" defer></script>
    <script src="../assets/js/demo.min.js?1692870487" defer></script>
    <script>
        function formatRupiah(angka) {
          return new Intl.NumberFormat('id-ID', {
              style: 'currency',
              currency: 'IDR',
              minimumFractionDigits: 0
          }).format(angka);
      }
    </script>
  </body>
</html>
