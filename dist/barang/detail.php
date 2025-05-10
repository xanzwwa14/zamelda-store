<?php
session_start();

class barangBase {
    protected $kon; 

    public function __construct($kon) {
        $this->kon = $kon;
    }

    protected function getBarangById($idBarang) {
        $sql = "SELECT * FROM barang p 
                INNER JOIN kategoribarang k ON k.idKategori = p.kodeKategori 
                WHERE p.idBarang = $idBarang LIMIT 1";
        $hasil = mysqli_query($this->kon, $sql);
        return mysqli_fetch_array($hasil);
    }
}

class barangDetails extends barangBase {

    public function displayDetails($idBarang) {
        $data = $this->getBarangById($idBarang);

        if (!$data) {
            echo "<div class='alert alert-danger'>Data barang tidak ditemukan.</div>";
            return;
        }

        echo '<div class="card-body">';
        if ($data['stok'] <= 0) {
            echo '<div class="alert alert-warning">Mohon maaf stok barang sedang kosong</div>';
        }

        echo '<div class="row">';
        echo '<div class="col-sm-6">';
        echo '<img class="card-img-top" src="gambar/gambar/' . $data['gambarBarang'] . '" alt="Card image">';
        echo '</div>';
        echo '<div class="col-sm-6">';
        echo '<table class="table">';
        echo '<tbody>';
        echo '<tr><td>Judul</td><td>: ' . $data['namaBarang'] . '</td></tr>';
        echo '<tr><td>Kategori</td><td>: ' . $data['namaKategoriBarang'] . '</td></tr>';
        echo '<tr><td>Jumlah Stok</td><td>: ' . $data['stok'] . '</td></tr>';


        if ($data['stok'] >= 1 && ($_SESSION['level'] == 'Pelanggan' || $_SESSION['level'] == 'pelanggan')) {
            echo '<tr><td colspan="2"><a href="index.php?page=keranjang&kodeBarang=' . $data['kodeBarang'] . '&aksi=pilih_barang" class="btn btn-dark btn-block">Masukan Keranjang</a></td></tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

include '../../config/database.php';

$idBarang = $_POST["idBarang"];

$barang = new barangDetails($kon);
?>

<div class="card">
    <?php $barang->displayDetails($idBarang); ?>
</div>
