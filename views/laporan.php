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
                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" onclick="printLaporan()">
                      <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-printer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                      Cetak Laporan
                    </a>
                      <div class="ms-auto text-secondary">
                        Search:
                        <div class="ms-2 d-inline-block">
                          <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                      <thead class="table-light">
                        <tr>
                          <th>No</th>
                          <th>ID</th>
                          <th>Kode Invoice</th>
                          <th>Tanggal</th>
                          <th>Dibayar</th>
                          <th>Total Price</th>
                        </tr>
                      </thead>
                      <tbody id="transaksiTableBody">
                            <tr>
                                <td colspan="6">Memuat data...</td>
                            </tr>
                        </tbody>
                        <!-- <tfoot>
                            <tr>
                                <th colspan="5">Total Keseluruhan</th>
                                <th id="totalKeseluruhan"></th>
                            </tr>
                        </tfoot> -->
                    </table>
                  </div>
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
    function printLaporan() {
        var printContent = document.querySelector('.table-responsive').innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        location.reload(); 
    }

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
        console.log("Response Data:", response); // Debugging: Cek data dari server

        if (response.success) {
            $("#transaksiTableBody").empty(); // Kosongkan tabel sebelum mengisi ulang

            if (response.data.length === 0) {
            $("#transaksiTableBody").append('<tr><td colspan="6" class="text-center">Tidak ada transaksi</td></tr>');
            return;
            }

            $.each(response.data, function(index, transaksi) {
            console.log("Transaksi:", transaksi); // Debugging: Cek tiap transaksi

            let statusText = capitalize(transaksi.status);
            let dibayarBadge = (transaksi.dibayar.toLowerCase() === "dibayar") 
                ? '<span class="badge bg-green text-green-fg">Dibayar</span>' 
                : '<span class="badge bg-red text-red-fg">Belum Dibayar</span>';

            const row = `<tr>
                <td>${index + 1}</td>
                <td>${transaksi.id}</td>
                <td>${transaksi.kode_invoice}</td>
                <td>${formatTanggal(transaksi.tgl)}</td>
                <td>${dibayarBadge}</td>
                <td>${formatRupiah(transaksi.total_harga)}</td>
            </tr>`;
            
            $("#transaksiTableBody").append(row);
            });
        } else {
            Swal.fire({
            icon: 'error',
            title: 'Gagal mengambil data!',
            text: response.message || 'Failed to fetch data.',
            confirmButtonText: 'OK'
            });
        }
        },
        error: function(xhr, status, error) {
        console.error("AJAX Error:", status, error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan saat mengambil data.',
            confirmButtonText: 'OK'
        });
        }
    });
    }

    // Fungsi untuk format tanggal ke tampilan lebih rapi
    function formatTanggal(tanggal) {
    let date = new Date(tanggal);
    return date.toLocaleDateString("id-ID", { year: 'numeric', month: 'long', day: 'numeric' });
    }

    fetchTransaksiData(); // Panggil fungsi saat halaman dimuat

  });

      function totalKeseluruhan() {
        let total = 0;
    $('#transaksiTableBody tr').each(function () {
        let totalPriceText = $(this).find("td:eq(5)").text().trim(); // Ambil teks dari kolom ke-6 (indeks 5)
        
        // Konversi format Rupiah ke angka
        let totalPrice = parseInt(totalPriceText.replace(/[^0-9]/g, '')) || 0;
        
        total += totalPrice;
    });

    // Format hasilnya ke dalam Rupiah dan tampilkan di footer
    $('#totalKeseluruhan').text(formatRupiah(total));
    }

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