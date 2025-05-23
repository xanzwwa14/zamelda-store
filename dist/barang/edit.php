<?php
session_start();
include '../../config/database.php';

function input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if (isset($_POST['edit_barang']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    mysqli_query($kon, "START TRANSACTION");

    $kodeBarang = input($_POST["kodeBarang"]);
    $namaBarang = input($_POST["namaBarang"]);
<<<<<<< HEAD
    $kodeKategori = input($_POST["kategoriBarang"]);

    $update_barang = mysqli_query($kon, "UPDATE barang SET namaBarang='$namaBarang', kodeKategori='$kodeKategori' WHERE kodeBarang='$kodeBarang'");

    // Hapus varian lama
    mysqli_query($kon, "DELETE FROM varianbarang WHERE kodeBarang='$kodeBarang'");

    $jumlah_varian = count($_FILES['gambarBarang']['name']);

    for ($i = 0; $i < $jumlah_varian; $i++) {
        $namaFile = $_FILES['gambarBarang']['name'][$i];
        $tmpName = $_FILES['gambarBarang']['tmp_name'][$i];
        $ukuran = $_FILES['gambarBarang']['size'][$i];
        $ekstensi = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        $allowed_ext = ['png', 'jpg', 'jpeg'];

        if (in_array($ekstensi, $allowed_ext) && $ukuran < 1044070) {
            $newName = uniqid() . '.' . $ekstensi;
            move_uploaded_file($tmpName, 'gambar/' . $newName);

            $ukuranVar = input($_POST['ukuran'][$i]);
            $varian = input($_POST['varian'][$i]);
            $harga = input($_POST['harga'][$i]);
            $stok = input($_POST['stok'][$i]);

            $sql_varian = "INSERT INTO varianbarang (kodeBarang, gambarBarang, ukuran, varian, harga, stok)
                           VALUES ('$kodeBarang', '$newName', '$ukuranVar', '$varian', '$harga', '$stok')";
            mysqli_query($kon, $sql_varian);
        }
    }

    if ($update_barang) {
=======
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
>>>>>>> ceeb341c16077fee39f33e460b172b58ed186d4e
        mysqli_query($kon, "COMMIT");
        header("Location:../../dist/index.php?page=barang&edit=berhasil");
    } else {
        mysqli_query($kon, "ROLLBACK");
        header("Location:../../dist/index.php?page=barang&edit=gagal");
    }
}

<<<<<<< HEAD
// Ambil data lama
$kodeBarang = $_GET['kodeBarang'];
$data_barang = mysqli_fetch_array(mysqli_query($kon, "SELECT * FROM barang WHERE kodeBarang='$kodeBarang'"));
$data_varian = mysqli_query($kon, "SELECT * FROM varianbarang WHERE kodeBarang='$kodeBarang'");
?>

<form action="barang/edit.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="kodeBarang" value="<?php echo $data_barang['kodeBarang']; ?>">
=======
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
>>>>>>> ceeb341c16077fee39f33e460b172b58ed186d4e

  <div class="row">
    <div class="col-sm-10">
      <div class="form-group">
        <label>Nama Barang:</label>
<<<<<<< HEAD
        <input name="namaBarang" type="text" class="form-control" value="<?php echo $data_barang['namaBarang']; ?>" required>
      </div>
    </div>
    <div class="col-sm-2">
      <div class="form-group">
        <label>Kode:</label>
        <h4><?php echo $data_barang['kodeBarang']; ?></h4>
      </div>
=======
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
>>>>>>> ceeb341c16077fee39f33e460b172b58ed186d4e
    </div>
  </div>

<<<<<<< HEAD
  <div class="form-group">
    <label>Kategori:</label>
    <select name="kategoriBarang" class="form-control">
      <?php
      $hasil = mysqli_query($kon, "SELECT * FROM kategoriBarang ORDER BY idKategori ASC");
      while ($data = mysqli_fetch_array($hasil)):
      ?>
      <option value="<?php echo $data['idKategori']; ?>" <?php if ($data['idKategori'] == $data_barang['kodeKategori']) echo "selected"; ?>>
        <?php echo $data['namaKategori']; ?>
      </option>
      <?php endwhile; ?>
    </select>
  </div>

  <hr>
  <h5>Edit Varian Barang</h5>
  <div id="form-varian">
    <?php foreach ($data_varian as $varian): ?>
    <div class="form-varian-item border p-3 mb-3">
      <div class="form-group">
        <label>Gambar Lama:</label><br>
        <img src="gambar/<?php echo $varian['gambarBarang']; ?>" width="100"><br>
        <label>Ganti Gambar:</label>
        <input type="file" name="gambarBarang[]" class="form-control-file">
      </div>
      <div class="form-group">
        <label>Ukuran:</label>
        <input type="text" name="ukuran[]" class="form-control" value="<?php echo $varian['ukuran']; ?>" required>
      </div>
      <div class="form-group">
        <label>Varian:</label>
        <input type="text" name="varian[]" class="form-control" value="<?php echo $varian['varian']; ?>" required>
      </div>
      <div class="form-group">
        <label>Harga:</label>
        <input type="number" name="harga[]" class="form-control" value="<?php echo $varian['harga']; ?>" required>
      </div>
      <div class="form-group">
=======
    <div class="form-group">
>>>>>>> ceeb341c16077fee39f33e460b172b58ed186d4e
        <label>Stok:</label>
        <input type="number" name="stok[]" class="form-control" value="<?php echo $varian['stok']; ?>" required>
      </div>
      <button type="button" class="btn btn-danger btn-sm remove-varian">Hapus Varian</button>
    </div>
    <?php endforeach; ?>
  </div>

  <button type="button" id="add-varian" class="btn btn-secondary mb-3">+ Tambah Varian</button>

  <div class="form-group">
    <button type="submit" name="edit_barang" class="btn btn-primary">Update Barang</button>
  </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('#add-varian').click(function () {
    let item = $('.form-varian-item').first().clone();
    item.find('input').val('');
    item.find('img').remove(); // remove preview image
    $('#form-varian').append(item);
  });

  $('#form-varian').on('click', '.remove-varian', function () {
    if ($('.form-varian-item').length > 1) {
      $(this).closest('.form-varian-item').remove();
    }
  });
</script>
