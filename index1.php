<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Registration | Politeknik Negeri Lhokseumawe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
    body {
        background-color: #1a0a3d;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .card-container {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 30px;
        width: 400px;
        text-align: center;
    }

    .rfid-card {
        background: url('https://placehold.co/400x200?text=RFID+Card') no-repeat center center;
        background-size: cover;
        border-radius: 10px;
        height: 200px;
        margin-bottom: 20px;
        position: relative;
        color: white;
        padding: 20px;
    }

    .rfid-card .card-number,
    .rfid-card .card-holder,
    .rfid-card .locker-number {
        position: absolute;
        font-size: 1.2em;
    }

    .rfid-card .card-number {
        top: 50%;
        left: 20px;
        transform: translateY(-50%);
    }

    .rfid-card .card-holder {
        bottom: 20px;
        left: 20px;
    }

    .rfid-card .locker-number {
        bottom: 20px;
        right: 20px;
    }

    .form-control {
        margin-bottom: 15px;
    }

    .btn-primary {
        width: 100%;
        padding: 10px;
        font-size: 1.2em;
    }
    </style>
</head>

<body>
    <div class="card-container">
        <div class="rfid-card">
            <div class="card-number">RFID: #### #### #### ####</div>
            <div class="card-holder">Nama Pemegang<br>FULL NAME</div>
            <div class="locker-number">Loker No: #</div>
        </div>
        <form action="register.php" method="POST" onsubmit="return validateForm()">
            <div class="mb-3">
                <input type="text" class="form-control" id="nama_mahasiswa" name="nama_mahasiswa"
                    placeholder="Nama Mahasiswa">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" id="nim" name="nim" placeholder="NIM">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" id="nomor_rfid" name="nomor_rfid" placeholder="Nomor RFID">
            </div>
            <div class="mb-3">
                <input type="number" class="form-control" id="nomor_loker" name="nomor_loker" placeholder="Nomor Loker">
            </div>
            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>
    </div>

    <script>
    function validateForm() {
        var nama = document.getElementById("nama_mahasiswa").value;
        var nim = document.getElementById("nim").value;
        var nomor_rfid = document.getElementById("nomor_rfid").value;
        var nomor_loker = document.getElementById("nomor_loker").value;

        if (nama == "" || nim == "" || nomor_rfid == "" || nomor_loker == "") {
            alert("Semua kolom harus diisi!");
            return false;
        }

        if (isNaN(nim)) {
            alert("NIM harus berupa angka!");
            return false;
        }

        return true;
    }
    </script>
</body>

</html>