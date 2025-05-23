<?php 
    session_start();
    include '../../config/database.php';

    mysqli_query($kon,"START TRANSACTION");

    $query = mysqli_query($kon, "SELECT max(idTransaksi) as idTransaksi_terbesar FROM peminjaman");
    $data = mysqli_fetch_array($query);
    $idTransaksi = $data['idTransaksi_terbesar'];
    $idTransaksi++;
    $kodePeminjaman = sprintf("%03s", $idTransaksi);
    $tanggal=date('Y-m-d');
    $kodeAnggota=$_SESSION['kodePengguna'];

    $simpan_tabel_peminjaman=mysqli_query($kon,"insert into peminjaman (kodePeminjaman,kodeAnggota,tanggal) values ('$kodePeminjaman','$kodeAnggota','$tanggal')");

    if(!empty($_SESSION["cart_barang"])) {
        foreach ($_SESSION["cart_barang"] as $item) {
            $kodeBarang=$item['kodeBarang'];
            $status = 0; 
            $denda = 0;   
            $simpan_tabel_detail=mysqli_query($kon,"insert into detail_peminjaman (kodePeminjaman, kodeBarang, tanggal) values ('$kodePeminjaman', '$kodeBarang', NOW(), CURRENT_DATE + INTERVAL 7 DAY, 0)");
        }
    }

    if ($simpan_tabel_peminjaman and $simpan_tabel_detail) {
        mysqli_query($kon,"COMMIT");

        unset($_SESSION["cart_barang"]);
        header("Location:../index.php?page=booking&kodePeminjaman=$kodePeminjaman");
    }
    else {
        mysqli_query($kon,"ROLLBACK");

        unset($_SESSION["cart_barang"]);
        header("Location:../index.php?page=booking&add=gagal");
    }
?>
