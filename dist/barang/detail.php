<?php
session_start();
include '../../config/database.php';

class BarangDetailCarousel {
    protected $kon;

    public function __construct($kon) {
        $this->kon = $kon;
    }

    protected function getBarang($idBarang) {
        $idBarang = mysqli_real_escape_string($this->kon, $idBarang);
        $sql = "SELECT b.*, k.namaKategori 
                FROM barang b
                INNER JOIN kategoribarang k ON k.kodeKategori = b.kodeKategori
                WHERE b.idBarang = '$idBarang' LIMIT 1";
        $result = mysqli_query($this->kon, $sql);
        return mysqli_fetch_assoc($result);
    }

    protected function getVarianBarang($kodeBarang) {
        $kodeBarang = mysqli_real_escape_string($this->kon, $kodeBarang);
        $sql = "SELECT * FROM varianbarang WHERE kodeBarang = '$kodeBarang'";
        $result = mysqli_query($this->kon, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function tampilkanDetail($idBarang) {
        $barang = $this->getBarang($idBarang);

        if (!$barang) {
            echo "<div class='alert alert-danger'>Barang tidak ditemukan.</div>";
            return;
        }

        $varians = $this->getVarianBarang($barang['kodeBarang']);

        if (count($varians) === 0) {
            echo "<div class='alert alert-warning'>Tidak ada varian tersedia.</div>";
            return;
        }

        echo "<h4>{$barang['namaBarang']} <small class='text-muted'>({$barang['namaKategori']})</small></h4>";

        echo '<div id="carouselVarianBarang" class="carousel slide" data-ride="carousel">';
        echo '<div class="carousel-inner">';

        foreach ($varians as $index => $varian) {
            $active = ($index === 0) ? 'active' : '';
            echo "<div class='carousel-item $active'>";
            echo "<div class='card text-center'>";
            echo "<img class='card-img' src='../dist/barang/gambar/{$varian['gambarBarang']}' alt='Varian Gambar'>";
            echo "<div class='card-body'>";
            echo "<p><strong>Ukuran:</strong> {$varian['size']}</p>";
            echo "<p><strong>Varian:</strong> {$varian['typeVarian']}</p>";
            echo "<p><strong>Stok:</strong> {$varian['stok']}</p>";
            echo "<p><strong>Harga:</strong> Rp" . number_format($varian['harga'], 0, ',', '.') . "</p>";

            if (strtolower($_SESSION['level'] ?? '') === 'pelanggan' && $varian['stok'] > 0) {
                echo "<a href='index.php?page=keranjang&kodeBarang={$barang['kodeBarang']}&kodeVarian={$varian['kodeVarian']}' class='btn btn-primary btn-sm'>";
                echo "<i class='fas fa-cart-plus'></i> Tambah ke Keranjang</a>";
            } elseif ($varian['stok'] <= 0) {
                echo "<div class='alert alert-warning p-1 text-center'>Stok Kosong</div>";
            }

            echo "    </div>";
            echo "  </div>";
            echo "</div>";
        }

        echo '</div>'; 

        echo '<a class="carousel-control-prev" href="#carouselVarianBarang" role="button" data-slide="prev">';
        echo '  <span class="carousel-control-prev-icon" aria-hidden="true"></span>';
        echo '  <span class="sr-only">Previous</span>';
        echo '</a>';
        echo '<a class="carousel-control-next" href="#carouselVarianBarang" role="button" data-slide="next">';
        echo '  <span class="carousel-control-next-icon" aria-hidden="true"></span>';
        echo '  <span class="sr-only">Next</span>';
        echo '</a>';

        echo '</div>'; 
    }
}


$idBarang = $_GET['idBarang'] ?? $_POST['idBarang'] ?? null;

if (!$idBarang) {
    echo "<div class='alert alert-danger'>ID Barang tidak ditemukan.</div>";
    exit;
}

$handler = new BarangDetailCarousel($kon);
?>

<div class="container">
    <?php $handler->tampilkanDetail($idBarang); ?>
</div>
