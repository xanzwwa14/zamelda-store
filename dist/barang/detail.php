<?php
session_start();

class barangBase {
    protected $kon; 

    public function __construct($kon) {
        $this->kon = $kon;
    }

    protected function getBarangById($idBarang) {
        $idBarang = mysqli_real_escape_string($this->kon, $idBarang);

        $sql = "SELECT * FROM barang p 
                INNER JOIN kategoribarang k ON k.kodeKategori = p.kodeKategori 
                WHERE p.idBarang = '$idBarang' LIMIT 1";

        $hasil = mysqli_query($this->kon, $sql);
        if (!$hasil) {
            die("Query error: " . mysqli_error($this->kon));
        }
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
        echo '<img class="card-img-top img-fluid" src="../dist/barang/gambar/' . htmlspecialchars($data['gambarBarang']) . '" alt="' . htmlspecialchars($data['namaBarang']) . '">';
        echo '</div>';
        echo '<div class="col-sm-6">';
        echo '<table class="table">';
        echo '<tbody>';
        echo '<tr><td>Judul</td><td>: ' . $data['namaBarang'] . '</td></tr>';
        echo '<tr><td>Kategori</td><td>: ' . $data['namaKategori'] . '</td></tr>';
        echo '<tr><td>Jumlah Stok</td><td>: ' . $data['stok'] . '</td></tr>';

        if ($data['stok'] >= 1 && (isset($_SESSION['level']) && strtolower($_SESSION['level']) === 'pelanggan')) {
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

$idBarang = $idBarang ?? $_GET['idBarang'] ?? $_POST['idBarang'] ?? null;

if (empty($idBarang)) {
    echo "<div class='alert alert-danger'>ID Barang tidak ditemukan di URL atau form.</div>";
    exit;
}

$barang = new barangDetails($kon);
?>

<div class="card">
    <?php $barang->displayDetails($idBarang); ?>
</div>
