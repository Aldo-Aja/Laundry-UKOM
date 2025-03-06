<?php
session_start();

// Validasi apakah user sudah login
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Jika user belum login, arahkan ke halaman login
    header("Location: ../public/login.php");
    exit();
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
    <title>Transaksi</title>
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
      .modal-backdrop {
        backdrop-filter: blur(300dp); /* Adjust blur intensity */
        background-color: rgba(0, 0, 0, 1); /* Slight dark overlay */
      }
    </style>
  </head>
  <body>
    <script src="./dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page">
      <!-- Navbar -->
      <?php include '../includes/navbar.php'?>
            <div class="page-body" style="margin-left: 240px;">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Data Transaksi</h3>
                  </div>
                  <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTransaksiModal">
                      <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M5 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4c.96 0 1.84 .338 2.53 .901" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                      </svg>
                      Tambah Transaksi
                    </a>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                      <thead class="table-light">
                        <tr>
                          <th>No</th>
                          <th>ID</th>
                          <th>Kode Invoice</th>
                          <th>ID Member</th>
                          <th>Tanggal</th>
                          <th>Status</th>
                          <th>Dibayar</th>
                          <th>Total Price</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="transaksiTableBody"></tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal for Adding Transaksi -->
            <div class="modal fade" id="addTransaksiModal" tabindex="-1" aria-labelledby="addTransaksiModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg rounded-3">
                  <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addTransaksiModalLabel">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="addTransaksiForm">
                      <!-- Outlet Select -->
                      <div class="mb-3">
                        <label for="id_outlet" class="form-label">Outlet</label>
                        <select name="id_outlet" id="id_outlet" class="form-select" required>
                          <option value="">Pilih Outlet</option>
                        </select>
                      </div>
                      <!-- Member Select -->
                      <div class="mb-3">
                        <label for="id_member" class="form-label">Member</label>
                        <select name="id_member" id="id_member" class="form-select" required>
                          <option value="">Pilih Member</option>
                        </select>
                      </div>
                      <!-- Paket Select (New Field) -->
                      <div class="mb-3">
                        <label for="id_paket" class="form-label">Paket</label>
                        <select name="id_paket" id="id_paket" class="form-select" required>
                          <option value="">Pilih Paket</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="qty" class="form-label">Jumlah (Qty)</label>
                        <input type="number" name="qty" id="qty" class="form-control" min="1" placeholder="Masukkan jumlah paket" required>
                      </div>
                      <!-- Diskon -->
                      <div class="mb-3">
                        <label for="diskon" class="form-label">Diskon (%)</label>
                        <input type="number" name="diskon" id="diskon" class="form-control" min="0" max="100" placeholder="Masukkan diskon (0-100)">
                      </div>
                      <!-- Biaya Tambahan -->
                      <div class="mb-3">
                        <label for="biaya_tambahan" class="form-label">Biaya Tambahan (Rp)</label>
                        <input type="number" name="biaya_tambahan" id="biaya_tambahan" class="form-control" min="0" placeholder="Masukkan biaya tambahan">
                      </div>
                      <!-- Tanggal -->
                      <div class="mb-3">
                        <label for="tgl" class="form-label">Tanggal</label>
                        <input type="datetime-local" name="tgl" class="form-control" required>
                      </div>
                      <!-- Batas Waktu -->
                      <div class="mb-3">
                        <label for="batas_waktu" class="form-label">Batas Waktu</label>
                        <input type="datetime-local" name="batas_waktu" class="form-control" required>
                      </div>
                      <!-- Status -->
                      <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                          <option value="">-- Pilih Status --</option>
                          <option value="baru">Baru</option>
                          <option value="proses">Proses</option>
                          <option value="selesai">Selesai</option>
                          <option value="diambil">Diambil</option>
                        </select>
                      </div>
                      <!-- Dibayar -->
                      <div class="mb-3">
                        <label for="dibayar" class="form-label">Dibayar</label>
                        <select name="dibayar" class="form-select" required>
                          <option value="">-- Pilih Status Pembayaran --</option>
                          <option value="dibayar">Dibayar</option>
                          <option value="belum_dibayar">Belum Dibayar</option>
                        </select>
                      </div>
                      <!-- Optionally, add additional fields (biaya_tambahan, diskon, etc.) -->
                      <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-md">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal for Editing Transaksi -->
            <div class="modal fade" id="editTransaksiModal" tabindex="-1" aria-labelledby="editTransaksiModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="editTransaksiModalLabel">Edit Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="editTransaksiForm">
                      <!-- Outlet Select -->
                      <div class="mb-3">
                        <label for="edit_id_outlet" class="form-label">Outlet</label>
                        <select name="id_outlet" id="edit_id_outlet" class="form-select" required>
                          <option value="">Pilih Outlet</option>
                        </select>
                      </div>
                      <!-- Member Select -->
                      <div class="mb-3">
                        <label for="edit_id_member" class="form-label">Member</label>
                        <select name="id_member" id="edit_id_member" class="form-select" required>
                          <option value="">Pilih Member</option>
                        </select>
                      </div>
                      <!-- Paket Select (New Field for Editing) -->
                      <div class="mb-3">
                        <label for="edit_id_paket" class="form-label">Paket</label>
                        <select name="id_paket" id="edit_id_paket" class="form-select" required>
                          <option value="">Pilih Paket</option>
                        </select>
                      </div>
                      <!-- Jumlah (Qty) -->
                      <div class="mb-3">
                        <label for="edit_qty" class="form-label">Jumlah (Qty)</label>
                        <input type="number" name="qty" id="edit_qty" class="form-control" min="1" placeholder="Masukkan jumlah paket" required>
                      </div>
                      <!-- Diskon -->
                      <div class="mb-3">
                        <label for="edit_diskon" class="form-label">Diskon (%)</label>
                        <input type="number" name="diskon" id="edit_diskon" class="form-control" min="0" max="100" placeholder="Masukkan diskon (0-100)">
                      </div>

                      <!-- Biaya Tambahan -->
                      <div class="mb-3">
                        <label for="edit_biaya_tambahan" class="form-label">Biaya Tambahan (Rp)</label>
                        <input type="number" name="biaya_tambahan" id="edit_biaya_tambahan" class="form-control" min="0" placeholder="Masukkan biaya tambahan">
                      </div>
                      <!-- Tanggal -->
                      <div class="mb-3">
                        <label for="edit_tgl" class="form-label">Tanggal</label>
                        <input type="datetime-local" id="edit_tgl" name="tgl" class="form-control" required>
                      </div>
                      <!-- Batas Waktu -->
                      <div class="mb-3">
                        <label for="edit_batas_waktu" class="form-label">Batas Waktu</label>
                        <input type="datetime-local" id="edit_batas_waktu" name="batas_waktu" class="form-control" required>
                      </div>
                      <!-- Status -->
                      <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select id="edit_status" name="status" class="form-select" required>
                          <option value="">-- Pilih Status --</option>
                          <option value="baru">Baru</option>
                          <option value="proses">Proses</option>
                          <option value="selesai">Selesai</option>
                          <option value="diambil">Diambil</option>
                        </select>
                      </div>
                      <!-- Dibayar -->
                      <div class="mb-3">
                        <label for="edit_dibayar" class="form-label">Dibayar</label>
                        <select id="edit_dibayar" name="dibayar" class="form-select" required>
                          <option value="">-- Pilih Status Pembayaran --</option>
                          <option value="dibayar">Dibayar</option>
                          <option value="belum_dibayar">Belum Dibayar</option>
                        </select>
                      </div>
                      <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
    <?php include '../includes/footer.php'?>
    </div>
  </div>
  <!-- Libs JS -->
  <script src="../assets/libs/nouislider/dist/nouislider.min.js?1692870487" defer></script>
  <script src="../assets/libs/litepicker/dist/litepicker.js?1692870487" defer></script>
  <script src="../assets/libs/tom-select/dist/js/tom-select.base.min.js?1692870487" defer></script>
  <!-- Tabler Core -->
  <script src="../assets/js/tabler.min.js?1692870487" defer></script>
  <script src="../assets/js/demo.min.js?1692870487" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
      function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }
    $(document).ready(function () {
    // Load data for outlet, member, and paket selects
    fetchOutletData();
    fetchMemberData();
    fetchPaketData();

    // Submit form to add new transaksi
    $("#addTransaksiForm").submit(function (e) {
      e.preventDefault();
      $.ajax({
        url: "../controllers/apitransaksi.php",
        type: "POST",
        data: $(this).serialize() + "&action=add",
        dataType: "json",
        success: function (response) {
          if (response.success) {
            Swal.fire({
              icon: 'success',
              title: 'Transaksi berhasil ditambahkan!',
              text: response.message,
              confirmButtonText: 'OK'
            }).then(() => {
              $('#addTransaksiModal').modal('hide');
              $("#addTransaksiForm")[0].reset();
              fetchTransaksiData();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: "Error: " + response.error,
              confirmButtonText: 'OK'
            });
          }
        },
        error: function (xhr, status, error) {
          console.log("XHR Response:", xhr.responseText); 
          console.log("Status:", status);
          console.log("Error:", error);
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'An error occurred while updating the data. Check console for details.',
            confirmButtonText: 'OK'
          });
        }
      });
    });

    // Function to fetch outlet data and populate select elements (for both add and edit modals)
    function fetchOutletData() {
      $.ajax({
        url: "../controllers/fetchoutlet.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
          if (response.success) {
            // Populate add modal select
            var outletSelect = $('#id_outlet');
            outletSelect.empty();
            outletSelect.append('<option value="">Pilih Outlet</option>');
            $.each(response.data, function (index, outlet) {
              outletSelect.append('<option value="' + outlet.id + '">' + outlet.nama + '</option>');
            });
            // Populate edit modal select
            var editOutletSelect = $('#edit_id_outlet');
            if(editOutletSelect.length){
              editOutletSelect.empty();
              editOutletSelect.append('<option value="">Pilih Outlet</option>');
              $.each(response.data, function (index, outlet) {
                editOutletSelect.append('<option value="' + outlet.id + '">' + outlet.nama + '</option>');
              });
            }
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal mengambil data outlet!',
              text: response.message,
              confirmButtonText: 'OK'
            });
          }
        },
        error: function () {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat mengambil data outlet. Silakan coba lagi.',
            confirmButtonText: 'OK'
          });
        }
      });
    }

    // Function to fetch member data and populate select elements
    function fetchMemberData() {
      $.ajax({
        url: "../controllers/fetchuser.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
          if (response.success) {
            var memberSelect = $('#id_member');
            memberSelect.empty();
            memberSelect.append('<option value="">Pilih Member</option>');
            $.each(response.data, function (index, member) {
              memberSelect.append('<option value="' + member.id + '">' + member.nama + '</option>');
            });
            // Populate edit modal select
            var editMemberSelect = $('#edit_id_member');
            if(editMemberSelect.length){
              editMemberSelect.empty();
              editMemberSelect.append('<option value="">Pilih Member</option>');
              $.each(response.data, function (index, member) {
                editMemberSelect.append('<option value="' + member.id + '">' + member.nama + '</option>');
              });
            }
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal mengambil data member!',
              text: response.message,
              confirmButtonText: 'OK'
            });
          }
        },
        error: function () {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat mengambil data member. Silakan coba lagi.',
            confirmButtonText: 'OK'
          });
        }
      });
    }

    // Function to fetch paket data and populate select elements
    function fetchPaketData() {
      $.ajax({
        url: "../controllers/fetchpaket.php",
        type: "GET",
        dataType: "json",
        success: function (response) {
          if (response.success) {
            var paketSelect = $('#id_paket');
            paketSelect.empty();
            paketSelect.append('<option value="">Pilih Paket</option>');
            $.each(response.data, function (index, paket) {
              paketSelect.append('<option value="' + paket.id + '">' + paket.nama_paket + ' (' + paket.jenis + ')</option>');
            });
            // Populate edit modal select
            var editPaketSelect = $('#edit_id_paket');
            if(editPaketSelect.length){
              editPaketSelect.empty();
              editPaketSelect.append('<option value="">Pilih Paket</option>');
              $.each(response.data, function (index, paket) {
                editPaketSelect.append('<option value="' + paket.id + '">' + paket.nama_paket + ' (' + paket.jenis + ')</option>');
              });
            }
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal mengambil data paket!',
              text: response.message,
              confirmButtonText: 'OK'
            });
          }
        },
        error: function () {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat mengambil data paket. Silakan coba lagi.',
            confirmButtonText: 'OK'
          });
        }
      });
    }

    // Helper function to convert underscore-separated text to capitalized words
    function capitalize(str) {
      if (!str) return "";
      // Replace underscores with space and capitalize first letter of each word
      return str.replace(/_/g, " ").replace(/\b\w/g, function(letter) {
        return letter.toUpperCase();
      });
    }

    function fetchTransaksiData() {
        $.ajax({
          url: "../controllers/fetchtransaksi.php",
          type: "GET",
          dataType: "json",
          success: function(response) {
            if (response.success) {
              $("#transaksiTableBody").empty();
              $.each(response.data, function(index, transaksi) {
                // Convert status field into human-readable text
                let statusText = capitalize(transaksi.status);
                
                // Determine badge based on payment status
                let dibayarBadge = (transaksi.dibayar.toLowerCase() === "dibayar") 
                  ? '<span class="badge bg-green text-green-fg">Dibayar</span>'
                  : '<span class="badge bg-red text-red-fg">Belum Dibayar</span>';
                
                const row = `<tr>
                  <td>${index + 1}</td>
                  <td>${transaksi.id}</td>
                  <td>${transaksi.kode_invoice}</td>
                  <td>${transaksi.id_member}</td>
                  <td>${transaksi.tgl}</td>
                  <td>${statusText}</td>
                  <td>${dibayarBadge}</td>
                  <td>${formatRupiah(transaksi.total_harga)}</td>
                  <td>
                    <span class="dropdown">
                      <button class="btn dropdown-toggle" data-bs-boundary="viewport" data-bs-toggle="dropdown">Actions</button>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#" onclick="editTransaksi(${transaksi.id})">Edit</a>
                        <a class="dropdown-item text-danger" href="#" onclick="deleteTransaksi(${transaksi.id})">Delete</a>
                        <a class="dropdown-item" href="#" onclick="printStruk(${transaksi.id})">Cetak Struk</a>
                      </div>
                    </span>
                  </td>
                </tr>`;
                $("#transaksiTableBody").append(row);
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Gagal mengambil data!',
                text: 'Failed to fetch data.',
                confirmButtonText: 'OK'
              });
            }
          }
        });
      }

    // Delete Transaksi
    window.deleteTransaksi = function (id) {
      Swal.fire({
        icon: 'warning',
        title: 'Apakah Anda yakin?',
        text: "Data ini akan dihapus secara permanen.",
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "../controllers/apitransaksi.php",
            type: "POST",
            data: { action: 'delete', id: id },
            dataType: "json",
            success: function (response) {
              if (response.success) {
                Swal.fire({
                  icon: 'success',
                  title: 'Transaksi berhasil dihapus!',
                  text: response.message,
                  confirmButtonText: 'OK'
                }).then(() => {
                  fetchTransaksiData();
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: "Error: " + response.error,
                  confirmButtonText: 'OK'
                });
              }
            }
          });
        }
      });
    };

    // Edit Transaksi
    window.editTransaksi = function(id) {
      $.ajax({
        url: "../controllers/fetchtransaksi.php",
        type: "GET",
        data: { id: id },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            const transaksi = response.data;
            // Populate selects in edit modal (assumes fetchPaketData() etc. have run)
            $('#edit_id_outlet').val(transaksi.id_outlet);
            $('#edit_id_member').val(transaksi.id_member);
            $('#edit_id_paket').val(transaksi.id_paket);
            // Convert datetime to "T" format for input datetime-local
            $('#edit_tgl').val(transaksi.tgl.replace(' ', 'T'));
            $('#edit_batas_waktu').val(transaksi.batas_waktu.replace(' ', 'T'));
            $('#edit_status').val(transaksi.status);
            $('#edit_dibayar').val(transaksi.dibayar);

            $('#editTransaksiModal').modal('show');

            $('#editTransaksiForm').off('submit').on('submit', function (e) {
              e.preventDefault();
              const updatedData = {
                id: id,
                id_outlet: $('#edit_id_outlet').val(),
                id_member: $('#edit_id_member').val(),
                id_paket: $('#edit_id_paket').val(),
                tgl: $('#edit_tgl').val().replace('T', ' '),
                batas_waktu: $('#edit_batas_waktu').val().replace('T', ' '),
                status: $('#edit_status').val(),
                dibayar: $('#edit_dibayar').val(),
                action: 'edit'
              };

              $.ajax({
                url: "../controllers/apitransaksi.php",
                type: "POST",
                data: updatedData,
                dataType: "json",
                success: function (response) {
                  if (response.success) {
                    Swal.fire({
                      icon: 'success',
                      title: 'Transaksi berhasil diperbarui!',
                      text: response.message,
                      confirmButtonText: 'OK'
                    }).then(() => {
                      $('#editTransaksiModal').modal('hide');
                      fetchTransaksiData();
                    });
                  } else {
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: "Error: " + response.error,
                      confirmButtonText: 'OK'
                    });
                  }
                },
                error: function (xhr, status, error) {
                  console.log("XHR Response:", xhr.responseText);
                  console.log("Status:", status);
                  console.log("Error:", error);
                  Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred while updating the data. Check console for details.',
                    confirmButtonText: 'OK'
                  });
                }
              });
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to fetch transaction data. Please try again.',
              confirmButtonText: 'OK'
            });
          }
        },
        error: function() {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while fetching the data. Please try again.',
            confirmButtonText: 'OK'
          });
        }
      });
    };

    // Print Struk (open print page in new window)
    window.printStruk = function(id) {
      window.open('../views/printstruk.php?id=' + id, '_blank');
    };

    // Initial fetch of transaksi data
    fetchTransaksiData();
  });

  </script>

  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	var el;
    	window.TomSelect && (new TomSelect(el = document.getElementById('select-states'), {
    		copyClassesToDropdown: false,
    		dropdownParent: 'body',
    		controlInput: '<input>',
    		render:{
    			item: function(data,escape) {
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    			option: function(data,escape){
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    		},
    	}));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	 window.noUiSlider && (noUiSlider.create(document.getElementById('range-simple'), {
    			  start: 20,
    			  connect: [true, false],
    			  step: 10,
    			  range: {
    				  min: 0,
    				  max: 100
    			  }
    	 }));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	 window.noUiSlider && (noUiSlider.create(document.getElementById('range-connect'), {
    			  start: [60, 90],
    			  connect: [false, true, false],
    			  step: 10,
    			  range: {
    				  min: 0,
    				  max: 100
    			  }
    	 }));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	 window.noUiSlider && (noUiSlider.create(document.getElementById('range-color'), {
    			  start: 40,
    			  connect: [true, false],
    			  step: 10,
    			  range: {
    				  min: 0,
    				  max: 100
    			  }
    	 }));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	window.Litepicker && (new Litepicker({
    		element: document.getElementById('datepicker-default'),
    		buttonText: {
    			previousMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>`,
    			nextMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`,
    		},
    	}));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	window.Litepicker && (new Litepicker({
    		element: document.getElementById('datepicker-icon'),
    		buttonText: {
    			previousMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>`,
    			nextMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`,
    		},
    	}));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	window.Litepicker && (new Litepicker({
    		element: document.getElementById('datepicker-icon-prepend'),
    		buttonText: {
    			previousMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>`,
    			nextMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`,
    		},
    	}));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	window.Litepicker && (new Litepicker({
    		element: document.getElementById('datepicker-inline'),
    		buttonText: {
    			previousMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>`,
    			nextMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`,
    		},
    		inlineMode: true,
    	}));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	var el;
    	window.TomSelect && (new TomSelect(el = document.getElementById('select-tags'), {
    		copyClassesToDropdown: false,
    		dropdownParent: 'body',
    		controlInput: '<input>',
    		render:{
    			item: function(data,escape) {
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    			option: function(data,escape){
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    		},
    	}));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	var el;
    	window.TomSelect && (new TomSelect(el = document.getElementById('select-users'), {
    		copyClassesToDropdown: false,
    		dropdownParent: 'body',
    		controlInput: '<input>',
    		render:{
    			item: function(data,escape) {
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    			option: function(data,escape){
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    		},
    	}));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	var el;
    	window.TomSelect && (new TomSelect(el = document.getElementById('select-optgroups'), {
    		copyClassesToDropdown: false,
    		dropdownParent: 'body',
    		controlInput: '<input>',
    		render:{
    			item: function(data,escape) {
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    			option: function(data,escape){
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    		},
    	}));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	var el;
    	window.TomSelect && (new TomSelect(el = document.getElementById('select-people'), {
    		copyClassesToDropdown: false,
    		dropdownParent: 'body',
    		controlInput: '<input>',
    		render:{
    			item: function(data,escape) {
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    			option: function(data,escape){
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    		},
    	}));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	var el;
    	window.TomSelect && (new TomSelect(el = document.getElementById('select-countries'), {
    		copyClassesToDropdown: false,
    		dropdownParent: 'body',
    		controlInput: '<input>',
    		render:{
    			item: function(data,escape) {
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    			option: function(data,escape){
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    		},
    	}));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	var el;
    	window.TomSelect && (new TomSelect(el = document.getElementById('select-labels'), {
    		copyClassesToDropdown: false,
    		dropdownParent: 'body',
    		controlInput: '<input>',
    		render:{
    			item: function(data,escape) {
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    			option: function(data,escape){
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    		},
    	}));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	var el;
    	window.TomSelect && (new TomSelect(el = document.getElementById('select-countries-valid'), {
    		copyClassesToDropdown: false,
    		dropdownParent: 'body',
    		controlInput: '<input>',
    		render:{
    			item: function(data,escape) {
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    			option: function(data,escape){
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    		},
    	}));
    });
    // @formatter:on
  </script>
  <script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	var el;
    	window.TomSelect && (new TomSelect(el = document.getElementById('select-countries-invalid'), {
    		copyClassesToDropdown: false,
    		dropdownParent: 'body',
    		controlInput: '<input>',
    		render:{
    			item: function(data,escape) {
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    			option: function(data,escape){
    				if( data.customProperties ){
    					return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
    				}
    				return '<div>' + escape(data.text) + '</div>';
    			},
    		},
    	}));
    });
    // @formatter:on
  </script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
    	let sliderTriggerList = [].slice.call(document.querySelectorAll("[data-slider]"));
    	sliderTriggerList.map(function (sliderTriggerEl) {
    		let options = {};
    		if (sliderTriggerEl.getAttribute("data-slider")) {
    			options = JSON.parse(sliderTriggerEl.getAttribute("data-slider"));
    		}
    		let slider = noUiSlider.create(sliderTriggerEl, options);
    		if (options['js-name']) {
    			window[options['js-name']] = slider;
    		}
    	});
    });
  </script>
</body>
</html>