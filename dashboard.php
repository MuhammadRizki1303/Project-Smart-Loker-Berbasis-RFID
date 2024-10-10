<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RFID Management | Politeknik Negeri Lhokseumawe</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />

    <style>
      body {
        background-color: #1a0a3d;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        color: white;
      }

      .table-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 90%;
        max-width: 1000px;
      }

      .btn-edit {
        background-color: #ffc107;
        border-color: #ffc107;
      }

      .btn-delete {
        background-color: #dc3545;
        border-color: #dc3545;
      }

      .status-used {
        color: green;
        font-weight: bold;
      }

      .status-unused {
        color: red;
        font-weight: bold;
      }
    </style>
  </head>

  <body>
    <div class="table-container">
      <h2 class="text-center mb-4">RFID Management</h2>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID RFID</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Nomor Loker</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="rfid-table-body">
          <tr>
            <td>#### #### #### ####</td>
            <td>Muhammad Rizki</td>
            <td>2022573010023</td>
            <td>10</td>
            <td><span class="status-used">Digunakan</span></td>
            <td>
              <button class="btn btn-delete btn-sm" onclick="deleteRow(this)">
                Hapus
              </button>
            </td>
          </tr>
          <tr>
            <td>#### #### #### ####</td>
            <td>John Doe</td>
            <td>2021573010045</td>
            <td>5</td>
            <td><span class="status-unused">Tidak Digunakan</span></td>
            <td>
              <button class="btn btn-delete btn-sm" onclick="deleteRow(this)">
                Hapus
              </button>
            </td>
          </tr>
          <!-- Tambahkan baris data lainnya sesuai kebutuhan -->
        </tbody>
      </table>
    </div>

    <!-- Script untuk memuat Bootstrap Bundle JS -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-sF0Ri6gKtVSmAV1k0pVtq2TnDQvvnnYJwJup9Lw59Mrzzfq3jqX3ZjtI8bbL03bI"
      crossorigin="anonymous"
    ></script>

    <script>
      // Fungsi untuk menghapus baris
      function deleteRow(button) {
        if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
          const row = button.parentElement.parentElement;
          row.remove();
        }
      }
    </script>
  </body>
</html>
