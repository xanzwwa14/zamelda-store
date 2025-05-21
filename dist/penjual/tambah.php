<?php
session_start();
if (isset($_POST['tambah_penjual'])) {

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include '../../config/database.php';
        mysqli_query($kon,"START TRANSACTION");

        $nama = input($_POST["nama"]);
        $no_telp = input($_POST["no_telp"]);
        $alamat = input($_POST["alamat"]);
        $status = input($_POST["status"]);

        $ekstensi_diperbolehkan = array('png','jpg','jpeg','gif');
        $foto = $_FILES['foto']['name'];
        $x = explode('.', $foto);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['foto']['size'];
        $file_tmp = $_FILES['foto']['tmp_name'];

        // Dapatkan ID terakhir
        $query = mysqli_query($kon, "SELECT max(idPenjual) as id_terbesar FROM penjual");
        $ambil = mysqli_fetch_array($query);
        $idPenjual = $ambil['id_terbesar'];
        $idPenjual++;
        $kodePenjual = "K" . sprintf("%03s", $idPenjual);

        // Handle upload foto
        if (!empty($foto) && in_array($ekstensi, $ekstensi_diperbolehkan)) {
            move_uploaded_file($file_tmp, 'foto/'.$foto);
        } else {
            $foto = "foto_default.png";
        }

        // Simpan ke tabel penjual
        $sql = "INSERT INTO penjual (kodePenjual, namaPenjual, foto, alamat, noTelp) VALUES 
                ('$kodePenjual', '$nama', '$foto', '$alamat', '$no_telp')";
        $simpan_penjual = mysqli_query($kon, $sql);

        // Simpan ke tabel pengguna
        $level = "penjual";
        $sql1 = "INSERT INTO pengguna (kodePengguna, status, level) VALUES 
                 ('$kodePenjual', '$status', '$level')";
        $simpan_pengguna = mysqli_query($kon, $sql1);

        if ($simpan_penjual && $simpan_pengguna) {
            mysqli_query($kon,"COMMIT");
            header("Location:../../dist/index.php?page=penjual&add=berhasil");
        } else {
            mysqli_query($kon,"ROLLBACK");
            header("Location:../../dist/index.php?page=penjual&add=gagal");
        }
    }
}
?>