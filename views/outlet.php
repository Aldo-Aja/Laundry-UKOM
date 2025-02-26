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
    <title>Outlet</title>
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
                    <h3 class="card-title">Data Outlet</h3>
                  </div>
                  <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                    <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addOutletModal">
                      <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M5 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4c.96 0 1.84 .338 2.53 .901" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                      </svg>
                      Tambah Outlet
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
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No Telpon</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="outletTableBody"></tbody>
                  </table>
                  </div>
                </div>
              </div>
            </div>

      <!-- Modal for Adding Outlet -->
      <div class="modal fade" id="addOutletModal" tabindex="-1" aria-labelledby="addOutletModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content shadow-lg rounded-3">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title" id="addOutletModalLabel">Tambah Outlet</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="addOutletForm">
                <div class="mb-3">
                  <input type="text" name="nama" class="form-control" placeholder="Nama" required>
                </div>
                <div class="mb-3">
                  <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
                </div>
                <div class="mb-3">
                  <input type="text" name="tlp" class="form-control" placeholder="No Telpon" required>
                </div>
                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary btn-md px-4">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade" id="editOutletModal" tabindex="-1" aria-labelledby="editOutletModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOutletModalLabel">Edit Outlet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editOutletForm">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" required>
                        </div>
                        <div class="mb-3">
                            <label for="tlp" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="tlp" required>
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
    $(document).ready(function () {
        $("#addOutletForm").submit(function (e) {
            e.preventDefault(); // Prevent page reload

            $.ajax({
                url: "../controllers/apioutlet.php", // Send to separate API
                type: "POST",
                data: $(this).serialize() + "&action=add",
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        // Use SweetAlert for success
                        Swal.fire({
                            icon: 'success',
                            title: 'Outlet berhasil ditambahkan!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Close the modal
                                $('#addOutletModal').modal('hide');
                                // Reset the form
                                $("#addOutletForm")[0].reset();
                                // Fetch updated data and update the table
                                fetchOutletData();
                            }
                        });
                    } else {
                        // Use SweetAlert for error
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

        //* Fetch Outlet data and update the table
        function fetchOutletData() {
            $.ajax({
                url: "../controllers/fetchoutlet.php", // Create a new PHP file to fetch data from the database
                type: "GET",
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        // Clear the current table
                        $("#outletTableBody").empty();

                        // Append new rows to the table
                        $.each(response.data, function (index, outlet) {
                            const row = `<tr>
                                <td>${index + 1}</td>
                                <td>${outlet.id}</td>
                                <td>${outlet.nama}</td>
                                <td>${outlet.alamat}</td>
                                <td>${outlet.tlp}</td>
                                <td>
                                      <span class="dropdown">
                                          <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport" data-bs-toggle="dropdown">Actions</button>
                                          <div class="dropdown-menu dropdown-menu-end">
                                              <a class="dropdown-item" href="#" onclick="editOutlet(${outlet.id})">Edit</a>
                                              <a class="dropdown-item text-danger" href="#" onclick="deleteOutlet(${outlet.id})">Delete</a>
                                          </div>
                                      </span>
                                  </td>
                            </tr>`;
                            $("#outletTableBody").append(row);
                        });
                    } else {
                        // Use SweetAlert for fetching data error
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
        //*DELETE USER
         window.deleteOutlet = function (id) {
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
                      url: "../controllers/apioutlet.php",
                      type: "POST",
                      data: { action: 'delete', id: id }, // Perbaikan di sini
                      dataType: "json",
                      success: function (response) {
                          if (response.success) {
                              Swal.fire({
                                  icon: 'success',
                                  title: 'Outlet berhasil dihapus!',
                                  text: response.message,
                                  confirmButtonText: 'OK'
                              }).then(() => {
                                  fetchOutletData(); // Refresh table setelah delete
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

        window.editOutlet = function(id) {
          $.ajax({
              url: "../controllers/fetchoutlet.php", // Fetch the data to edit
              type: "GET",
              data: { id: id },
              dataType: "json",
              success: function (response) {
                  if (response.success) {
                      // Fill the form with existing data
                      const outlet = response.data;
                      $('#editOutletModal #nama').val(outlet.nama); // Auto-fill name
                      $('#editOutletModal #alamat').val(outlet.alamat); // Auto-fill address
                      $('#editOutletModal #tlp').val(outlet.tlp); // Auto-fill phone number

                      // Show the modal
                      $('#editOutletModal').modal('show');
                      
                      // Handle form submission for editing
                      $('#editOutletForm').off('submit').on('submit', function (e) {
                          e.preventDefault(); // Prevent page reload

                          // Validate form data
                          const nama = $('#editOutletModal #nama').val();
                          const alamat = $('#editOutletModal #alamat').val();
                          const tlp = $('#editOutletModal #tlp').val();

                          if (!nama || !alamat || !tlp) {
                              Swal.fire({
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: 'Please fill in all fields!',
                                  confirmButtonText: 'OK'
                              });
                              return;
                          }

                          // Gather the form data
                          const updatedData = {
                              id: id, // Pass the id of the user being edited
                              nama: nama,
                              alamat: alamat,
                              tlp: tlp,
                              action: 'edit' // Specify the action as 'edit'
                          };

                          // Send the updated data to the backend
                          $.ajax({
                              url: "../controllers/apioutlet.php", // Send to the API for editing
                              type: "POST",
                              data: updatedData,
                              dataType: "json",
                              success: function (response) {
                                  if (response.success) {
                                      Swal.fire({
                                          icon: 'success',
                                          title: 'Outlet berhasil diperbarui!',
                                          text: response.message,
                                          confirmButtonText: 'OK'
                                      }).then((result) => {
                                          if (result.isConfirmed) {
                                              $('#editOutletModal').modal('hide'); // Hide the modal
                                              fetchOutletData(); // Refresh the table with updated data
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
                          text: 'Failed to fetch user data. Please try again.',
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


        // Initial fetch of data when the page loads
        fetchOutletData();
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