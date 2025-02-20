<?php
session_start();
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Regis Pelanggan</title>
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
        backdrop-filter: blur(300dp);
        background-color: rgba(0, 0, 0, 1);
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
              <h3 class="card-title">Data Pelanggan</h3>
            </div>
            <div class="card-body border-bottom py-3">
              <div class="d-flex">
                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPenggunaModal">
                  <!-- Icon SVG -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-plus">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M5 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4c.96 0 1.84 .338 2.53 .901" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    <path d="M16 19h6" />
                    <path d="M19 16v6" />
                  </svg>
                  Tambah Pelanggan
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
                    <th>Outlet</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <!-- Menggunakan userTablebody -->
                <tbody id="userTablebody"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Tambah Pelanggan -->
      <div class="modal fade" id="addPenggunaModal" tabindex="-1" aria-labelledby="addPenggunaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content shadow-lg rounded-3">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="addUserModalLabel">Tambah Pelanggan</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="addUserForm">
                <div class="mb-3">
                  <label for="id_outlet" class="form-label">Outlet</label>
                  <select name="id_outlet" id="id_outlet" class="form-select" required>
                    <option value="">Pilih Outlet</option>
                  </select>
                </div>
                <div class="mb-3">
                  <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                  <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="mb-3">
                  <input type="text" name="nama" class="form-control" placeholder="Nama" required>
                </div>
                <div class="mb-3">
                  <select name="role" class="form-select" required>
                    <option value="admin">Admin</option>
                    <option value="kasir">Kasir</option>
                    <option value="owner">Owner</option>
                  </select>
                </div>
                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary btn-md px-4">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Edit Pelanggan (hanya field id_outlet, nama, username, password yang bisa diedit) -->
      <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editUserModalLabel">Edit Pelanggan</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="editUserForm">
                <!-- Hidden input untuk menyimpan id user -->
                <input type="hidden" id="edit_id" name="id">
                <div class="mb-3">
                  <label for="edit_id_outlet" class="form-label">Outlet</label>
                  <select name="id_outlet" id="edit_id_outlet" class="form-select" required>
                    <option value="">Pilih Outlet</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="edit_username" class="form-label">Username</label>
                  <input type="text" class="form-control" id="edit_username" name="username" required>
                </div>
                <div class="mb-3">
                  <label for="edit_nama" class="form-label">Nama</label>
                  <input type="text" class="form-control" id="edit_nama" name="nama" required>
                </div>
                <div class="mb-3">
                  <label for="edit_password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="edit_password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <?php include '../includes/footer.php'?>
    </div>

    <!-- Libs JS -->
    <script src="../assets/libs/nouislider/dist/nouislider.min.js?1692870487" defer></script>
    <script src="../assets/libs/litepicker/dist/litepicker.js?1692870487" defer></script>
    <script src="../assets/libs/tom-select/dist/js/tom-select.base.min.js?1692870487" defer></script>
    <!-- Tabler Core -->
    <script src="../assets/js/tabler.min.js?1692870487" defer></script>
    <script src="../assets/js/demo.min.js?1692870487" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- JS file khusus -->
    <script src="../assets/js/pelanggan.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
      $(document).ready(function () {
        // Ambil data outlet untuk dropdown di modal tambah dan edit
        function fetchOutletData() {
          $.ajax({
            url: "../controllers/fetchoutlet.php",
            type: "GET",
            dataType: "json",
            success: function (response) {
              if (response.success) {
                // Modal tambah
                var outletSelect = $('#id_outlet');
                outletSelect.empty().append('<option value="">Pilih Outlet</option>');
                $.each(response.data, function (index, outlet) {
                  outletSelect.append('<option value="' + outlet.id + '">' + outlet.nama + '</option>');
                });
                // Modal edit
                var editOutletSelect = $('#edit_id_outlet');
                editOutletSelect.empty().append('<option value="">Pilih Outlet</option>');
                $.each(response.data, function (index, outlet) {
                  editOutletSelect.append('<option value="' + outlet.id + '">' + outlet.nama + '</option>');
                });
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

        // Fungsi untuk mengambil data pengguna dan mengisi tabel (menggunakan userTablebody)
        function fetchPenggunaData() {
          $.ajax({
            url: "../controllers/fetchpengguna.php",
            type: "GET",
            dataType: "json",
            success: function (response) {
              if (response.success) {
                $("#userTablebody").empty();
                $.each(response.data, function (index, user) {
                  const row = `<tr>
                      <td>${index + 1}</td>
                      <td>${user.id}</td>
                      <td>${user.id_outlet}</td>
                      <td>${user.username}</td>
                      <td>${user.nama}</td>
                      <td>${user.role}</td>
                      <td>
                        <span class="dropdown">
                          <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown">Actions</button>
                          <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#" onclick="editPengguna(${user.id})">Edit</a>
                            <a class="dropdown-item text-danger" href="#" onclick="deletePengguna(${user.id})">Delete</a>
                          </div>
                        </span>
                      </td>
                    </tr>`;
                  $("#userTablebody").append(row);
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Gagal mengambil data!',
                  text: 'Failed to fetch data.',
                  confirmButtonText: 'OK'
                });
              }
            },
            error: function () {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat mengambil data pengguna.',
                confirmButtonText: 'OK'
              });
            }
          });
        }

        // Tambah Pelanggan
        $("#addUserForm").submit(function (e) {
          e.preventDefault();
          $.ajax({
            url: "../controllers/apiuser.php",
            type: "POST",
            data: $(this).serialize() + "&action=add",
            dataType: "json",
            success: function (response) {
              if (response.success) {
                Swal.fire({
                  icon: 'success',
                  title: 'User berhasil ditambahkan!',
                  text: response.message,
                  confirmButtonText: 'OK'
                }).then((result) => {
                  if (result.isConfirmed) {
                    $('#addUserModal').modal('hide');
                    $("#addUserForm")[0].reset();
                    fetchPenggunaData();
                  }
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
        });

        // Hapus Pelanggan
        window.deletePengguna = function (id) {
          Swal.fire({
            icon: 'warning',
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus secara permanen.",
            showCancelButton: true,
            confirmButtonText: 'Yes, Hapus!',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                url: "../controllers/apiuser.php",
                type: "POST",
                data: { action: 'delete', id: id },
                dataType: "json",
                success: function (response) {
                  if (response.success) {
                    Swal.fire({
                      icon: 'success',
                      title: 'Pengguna berhasil dihapus!',
                      text: response.message,
                      confirmButtonText: 'OK'
                    }).then(() => {
                      fetchPenggunaData();
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

        // Edit Pelanggan (hanya field id_outlet, nama, username, dan password yang dapat diedit)
        window.editPengguna = function(id) {
          $.ajax({
            url: "../controllers/fetchpengguna.php",
            type: "GET",
            data: { id: id },
            dataType: "json",
            success: function (response) {
              if (response.success) {
                const user = response.data;
                // Isi form edit dengan data yang diambil
                $('#edit_id').val(user.id);
                $('#edit_id_outlet').val(user.id_outlet);
                $('#edit_username').val(user.username);
                $('#edit_nama').val(user.nama);
                // Kosongkan password agar user mengisi password baru jika diinginkan
                $('#edit_password').val('');
                // Tampilkan modal edit
                $('#editUserModal').modal('show');

                // Tangani submit form edit
                $('#editUserForm').off('submit').on('submit', function (e) {
                  e.preventDefault();
                  const updatedData = {
                    id: $('#edit_id').val(),
                    id_outlet: $('#edit_id_outlet').val(),
                    username: $('#edit_username').val(),
                    nama: $('#edit_nama').val(),
                    password: $('#edit_password').val(),
                    action: 'edit'
                  };
                  $.ajax({
                    url: "../controllers/apiuser.php",
                    type: "POST",
                    data: updatedData,
                    dataType: "json",
                    success: function (response) {
                      if (response.success) {
                        Swal.fire({
                          icon: 'success',
                          title: 'Pelanggan berhasil diperbarui!',
                          text: response.message,
                          confirmButtonText: 'OK'
                        }).then((result) => {
                          if (result.isConfirmed) {
                            $('#editUserModal').modal('hide');
                            fetchPenggunaData();
                          }
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
                      Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred while updating the data.',
                        confirmButtonText: 'OK'
                      });
                    }
                  });
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: 'Failed to fetch user data. Please try again.',
                  confirmButtonText: 'OK'
                });
              }
            },
            error: function () {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while fetching the data. Please try again.',
                confirmButtonText: 'OK'
              });
            }
          });
        };

        // Inisialisasi data outlet dan data pengguna saat halaman dimuat
        fetchOutletData();
        fetchPenggunaData();
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