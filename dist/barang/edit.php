<?php
session_start();
include '../../config/database.php';

function input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['edit_barang'])) {
    mysqli_query($kon, "START TRANSACTION");

    $idBarang = input($_POST["idBarang"]);
    $kodeBarang = input($_POST["kodeBarang"]);
    $kodeKategori = input($_POST["kodeKategori"]);
    $namaBarang = input($_POST["namaBarang"]);
    $stok = input($_POST["stok"]);
    $idVarian = input($_POST["idVarian"]);
    $gambarBarang = $_POST["gambar_saat_ini"];

    // Cek apakah admin upload gambar baru
    $gambar_baru = $_FILES['gambar_baru']['name'];
    $ekstensi_diperbolehkan = array('png', 'jpg');
    $x = explode('.', $gambar_baru);
    $ekstensi = strtolower(end($x));
    $ukuran_file = $_FILES['gambar_baru']['size'];
    $file_tmp = $_FILES['gambar_baru']['tmp_name'];

    if (!empty($gambar_baru)) {
        if (in_array($ekstensi, $ekstensi_diperbolehkan)) {
            if ($ukuran_file < 2044070) {
                move_uploaded_file($file_tmp, 'gambar/' . $gambar_baru);
                if (file_exists("gambar/" . $gambarBarang)) {
                    unlink("gambar/" . $gambarBarang);
                }
                $gambarBarang = $gambar_baru;
            }
        }
    }

    // Update tabel barang
    $sql_barang = "UPDATE barang SET 
                    kodeBarang='$kodeBarang', 
                    kodeKategori='$kodeKategori', 
                    namaBarang='$namaBarang' 
                   WHERE idBarang='$idBarang'";
    $update_barang = mysqli_query($kon, $sql_barang);

    // Update tabel varianBarang
    $sql_varian = "UPDATE varianBarang SET 
                    gambarBarang='$gambarBarang', 
                    stok='$stok' 
                   WHERE idVarian='$idVarian'";
    $update_varian = mysqli_query($kon, $sql_varian);

    if ($update_barang && $update_varian) {
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
        echo "ID barang tidak valid."; exit;
    }

    $stmt = $kon->prepare("SELECT barang *, idVarian, gambarBarang, stok 
                           FROM barang 
                           LEFT JOIN varianBarang  ON idBarang = idBarang 
                           WHERE idBarang = ?");
    $stmt->bind_param("i", $idBarang);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($data = $result->fetch_assoc()) {
        $kodeBarang = $data['kodeBarang'];
        $kodeKategori = $data['kodeKategori'];
        $namaBarang = $data['namaBarang'];
        $gambarBarang = $data['gambarBarang'];
        $stok = $data['stok'];
        $idVarian = $data['idVarian'];
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

<!-- FORM HTML -->
<form action="edit.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="idBarang" value="<?php echo $idBarang; ?>">
    <input type="hidden" name="idVarian" value="<?php echo $idVarian; ?>">
    <input type="hidden" name="kodeBarang" value="<?php echo $kodeBarang; ?>">
    <input type="hidden" name="gambar_saat_ini" value="<?php echo $gambarBarang; ?>">

    <div class="form-group">
        <label>Nama Barang:</label>
        <input name="namaBarang" type="text" value="<?php echo $namaBarang; ?>" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Kategori:</label>
        <select name="kodeKategori" class="form-control">
            <?php
            $sql = "SELECT * FROM kategori ORDER BY idKategori ASC";
            $hasil = mysqli_query($kon, $sql);
            while ($kat = mysqli_fetch_array($hasil)):
            ?>
                <option value="<?php echo $kat['idKategori']; ?>" 
                    <?php if ($kodeKategori == $kat['idKategori']) echo "selected"; ?>>
                    <?php echo $kat['namaKategori']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

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
