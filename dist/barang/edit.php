<?php
session_start();
include '../../config/database.php';

function input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['edit_barang'])) {
    mysqli_query($kon, "START TRANSACTION");

    $idBarang = input($_POST["idBarang"]);
    $kodeBarang = input($_POST["kodeBarang"]);
    $kodeKategori = input($_POST["kodeKategori"]);
    $namaBarang = input($_POST["namaBarang"]);
    $gambar_saat_ini = $_POST['gambar_saat_ini'];
    $stok = input($_POST["stok"]);
    // $warna = input($_POST["warna"]);
    // $ukuran = input($_POST["ukuran"]);

    $gambar_baru = $_FILES['gambar_baru']['name'];
    $ekstensi_diperbolehkan = array('png', 'jpg');
    $x = explode('.', $gambar_baru);
    $ekstensi = strtolower(end($x));
    $ukuran_file = $_FILES['gambar_baru']['size'];
    $file_tmp = $_FILES['gambar_baru']['tmp_name'];

    if (!empty($gambar_baru)) {
        if (in_array($ekstensi, $ekstensi_diperbolehkan)) {
            if ($ukuran_file < 2044070) {
                move_uploaded_file($file_tmp, 'gambar/'.$gambar_baru);
                if (file_exists("gambar/".$gambar_saat_ini)) {
                    unlink("gambar/".$gambar_saat_ini);
                }

                $sql = "UPDATE barang SET
                        kodeBarang='$kodeBarang',
                        kodeKategori='$kodeKategori',
                        namaBarang='$namaBarang',
                        gambarBarang='$gambar_baru',
                        stok='$stok',
                        warna='$warna',
                        ukuran='$ukuran'
                        WHERE idBarang='$idBarang'";
            }
        }
    } else {
        $sql = "UPDATE barang SET
                kodeBarang='$kodeBarang',
                kodeKategori='$kodeKategori',
                namaBarang='$namaBarang',
                stok='$stok',
                warna='$warna',
                ukuran='$ukuran'
                WHERE idBarang='$idBarang'";
    }

    $edit_barang = mysqli_query($kon, $sql);

    if ($edit_barang) {
        mysqli_query($kon, "COMMIT");
        header("Location:../../dist/index.php?page=barang&edit=berhasil");
    } else {
        mysqli_query($kon, "ROLLBACK");
        header("Location:../../dist/index.php?page=barang&edit=gagal");
    }
    exit;
}


if (isset($_POST['idBarang'])) {
    $idBarang = intval($_POST['idBarang']);

    if ($idBarang <= 0) {
        echo "ID barang tidak valid.";
        exit;
    }

    $stmt = $kon->prepare("SELECT * FROM barang WHERE idBarang = ?");
    $stmt->bind_param("i", $idBarang);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($data = $result->fetch_assoc()) {
        $kodeBarang = $data['kodeBarang'];
        $kodeKategori = $data['kodeKategori'];
        $namaBarang = $data['namaBarang'];
        $gambarBarang = $data['gambarBarang'];
        $stok = $data['stok'];
        // $warna = $data['warna'];
        // $ukuran = $data['ukuran'];
    } else {
        echo "Barang tidak ditemukan.";
        exit;
    }

    $stmt->close();
} else {
    echo "ID barang tidak disediakan.";
    exit;
}
?>

<form action="edit.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="idbarang" value="<?php echo $idBarang; ?>">
    <input type="hidden" name="kode" value="<?php echo $kodeBarang; ?>">
    <input type="hidden" name="gambar_saat_ini" value="<?php echo $gambarBarang; ?>">

    <div class="form-group">
        <label>Nama Barang:</label>
        <input name="nama_barang" type="text" value="<?php echo $namaBarang; ?>" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Kategori:</label>
        <select name="kategori" class="form-control">
            <?php
            $sql="SELECT * FROM kategori ORDER BY idKategori ASC";
            $hasil=mysqli_query($kon, $sql);
            while ($kat = mysqli_fetch_array($hasil)):
            ?>
                <option <?php if ($kodeKategori == $kat['idKategori']) echo "selected"; ?>
                    value="<?php echo $kat['idKategori']; ?>">
                    <?php echo $kat['namaKategori']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <!-- <div class="form-group">
        <label>Warna:</label>
        <input name="warna" type="text" value="<?php echo $warna; ?>" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Ukuran:</label>
        <input name="ukuran" type="text" value="<?php echo $ukuran; ?>" class="form-control" required>
    </div> -->

    <div class="form-group">
        <label>Stok:</label>
        <input name="stok" type="number" value="<?php echo $stok; ?>" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Gambar Saat Ini:</label><br>
        <img src="gambar/<?php echo $gambarBarang; ?>" width="150" class="img-thumbnail">
    </div>

    <div class="form-group">
        <label>Gambar Baru (Opsional):</label>
        <input type="file" name="gambar_baru" class="form-control-file">
    </div>

    <button type="submit" name="edit_barang" class="btn btn-success">Update</button>
</form>
