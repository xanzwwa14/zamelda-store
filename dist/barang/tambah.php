<?php
session_start();
include '../../config/database.php';

function input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['tambah_pelanggan']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    mysqli_query($kon, "START TRANSACTION");

    $kodeBarang = input($_POST["kodeBarang"]);
    $namaBarang = input($_POST["namaBarang"]);
    $kodeKategori = input($_POST["kategoriBarang"]);

    $simpan_barang = mysqli_query($kon, "INSERT INTO barang (kodeBarang, namaBarang, kodeKategori) VALUES ('$kodeBarang', '$namaBarang', '$kodeKategori')");

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

            $sql_varian = "INSERT INTO variabarang (kodeBarang, gambarBarang, ukuran, varian, harga, stok)
                           VALUES ('$kodeBarang', '$newName', '$ukuranVar', '$varian', '$harga', '$stok')";
            mysqli_query($kon, $sql_varian);
        }
    }

    if ($simpan_barang) {
        mysqli_query($kon, "COMMIT");
        header("Location:../../dist/index.php?page=barang&add=berhasil");
    } else {
        mysqli_query($kon, "ROLLBACK");
        header("Location:../../dist/index.php?page=barang&add=gagal");
    }
}

$query = mysqli_query($kon, "SELECT max(idBarang) as kodeTerbesar FROM barang");
$data = mysqli_fetch_array($query);
$idBarang = $data['kodeTerbesar'] + 1;
$kodeBarang = "br" . sprintf("%03s", $idBarang);
?>

<form action="barang/tambah.php" method="post" enctype="multipart/form-data">
  <div class="row">
    <div class="col-sm-10">
      <div class="form-group">
        <label>Nama Barang:</label>
        <input name="namaBarang" type="text" class="form-control" required>
      </div>
    </div>
    <div class="col-sm-2">
      <div class="form-group">
        <label>Kode:</label>
        <h4><?php echo $kodeBarang; ?></h4>
        <input name="kodeBarang" value="<?php echo $kodeBarang; ?>" type="hidden">
      </div>
    </div>
  </div>

  <div class="form-group">
    <label>Kategori:</label>
    <select name="kategoriBarang" class="form-control">
      <?php
      $hasil = mysqli_query($kon, "SELECT * FROM kategoriBarang ORDER BY idKategori ASC");
      while ($data = mysqli_fetch_array($hasil)):
      ?>
      <option value="<?php echo $data['idKategori']; ?>"><?php echo $data['namaKategori']; ?></option>
      <?php endwhile; ?>
    </select>
  </div>

  <hr>
  <h5>Data Varian Barang</h5>
  <div id="form-varian">
    <div class="form-varian-item border p-3 mb-3">
      <div class="form-group">
        <label>Gambar Varian:</label>
        <input type="file" name="gambarBarang[]" required class="form-control-file">
      </div>
      <div class="form-group">
        <label>Ukuran:</label>
        <input type="text" name="ukuran[]" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Varian:</label>
        <input type="text" name="varian[]" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Harga:</label>
        <input type="number" name="harga[]" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Stok:</label>
        <input type="number" name="stok[]" class="form-control" required>
      </div>
      <button type="button" class="btn btn-danger btn-sm remove-varian">Hapus Varian</button>
    </div>
  </div>

  <button type="button" id="add-varian" class="btn btn-secondary mb-3">+ Tambah Varian</button>

  <div class="form-group">
    <button type="submit" name="tambah_pelanggan" class="btn btn-success">Tambah Barang</button>
  </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $('#add-varian').click(function () {
    let item = $('.form-varian-item').first().clone();
    item.find('input').val('');
    $('#form-varian').append(item);
  });

  $('#form-varian').on('click', '.remove-varian', function () {
    if ($('.form-varian-item').length > 1) {
      $(this).closest('.form-varian-item').remove();
    }
  });
</script>
