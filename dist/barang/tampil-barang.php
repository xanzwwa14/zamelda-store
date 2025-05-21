<?php
session_start();
include '../../config/database.php';

$kategori = "";

if (isset($_POST['kategoriBarang'])) {
    foreach ($_POST['kategoriBarang'] as $value) {
        $kategori .= "'$value',";
    }
    $kategori = rtrim($kategori, ',');
} else {
    $kategori = "0";
}

$sql = isset($_POST['kodeKategori']) ?
    "SELECT b.idBarang, b.kodeBarang, b.namaBarang,
        (SELECT vv.gambarBarang FROM varianbarang vv WHERE vv.kodeBarang = b.kodeBarang ORDER BY vv.idVarian ASC LIMIT 1) AS gambarBarang
     FROM barang b
     WHERE b.kodeKategori IN ($kategori)" :
    "SELECT b.idBarang, b.kodeBarang, b.namaBarang,
        (SELECT vv.gambarBarang FROM varianbarang vv WHERE vv.kodeBarang = b.kodeBarang ORDER BY vv.idVarian ASC LIMIT 1) AS gambarBarang
     FROM barang b";


$hasil = mysqli_query($kon, $sql);
$cek = mysqli_num_rows($hasil);

if ($cek <= 0) {
    echo "<div class='col-sm-12'><div class='alert alert-warning'>Data tidak ditemukan!</div></div>";
    exit;
}

$barangs = mysqli_fetch_all($hasil, MYSQLI_ASSOC);
?>

<?php if ($_SESSION['level'] === 'Penjual' || $_SESSION['level'] === 'penjual'): ?>
    <div class="row">
        <div class="col-sm-2">
            <div class="form-group">
                <button type="button" id="btn-tambah-barang" class="btn btn-warning">
                    <i class="fas fa-book fa-sm"></i> Tambah barang
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive col-sm-12">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama Barang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barangs as $no => $data): ?>
                    <tr>
                        <td><?= $no + 1 ?></td>
                        <td><img src="../dist/barang/gambar/<?= htmlspecialchars($data['gambarBarang']) ?>" width="60"></td>
                        <td><?= htmlspecialchars($data['namaBarang']) ?></td>
                        <td>
                            <button type="button" class="btn-detail-barang btn btn-sm btn-info" idBarang="<?= $data['idBarang'] ?>" kodeBarang="<?= $data['kodeBarang'] ?>"><i class="fas fa-eye"></i></button>
                            <button type="button" class="btn-edit-barang btn btn-sm btn-warning" idBarang="<?= $data['idBarang'] ?>" kodeBarang="<?= $data['kodeBarang'] ?>"><i class="fas fa-edit"></i></button>
                            <a href="barang/hapus.php?idBarang=<?= $data['idBarang'] ?>&gambarBarang=<?= $data['gambarBarang'] ?>" class="btn-hapus btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php else: ?>
    <div class="row">
        <?php foreach ($barangs as $data): ?>
            <div class="col-sm-2">
                <div class="card">
                    <img class="card-img-top img-fluid" src="../dist/barang/gambar/<?= htmlspecialchars($data['gambarBarang']) ?>" alt="<?= htmlspecialchars($data['namaBarang']) ?>">
                    <div class="card-body text-center">
                        <button type="button" class="btn-detail-barang btn btn-warning btn-block"
                                idBarang="<?= $data['idBarang'] ?>" 
                                kodeBarang="<?= $data['kodeBarang'] ?>">
                            Lihat
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


<!-- Modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title" id="namaBarang"></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Bagian body -->
        <div class="modal-body">
            <div id="tampil_data">

            </div>  
        </div>
        <!-- Bagian footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </div>
    </div>
</div>


<script>
    $('#btn-tambah-barang').on('click',function(){
        $.ajax({
            url: 'barang/tambah.php',
            method: 'post',
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("namaBarang").innerHTML='Tambah Barang Baru';
            }
        });
        $('#modal').modal('show');
    });

    $('.btn-detail-barang').on('click',function(){
		var idBarang = $(this).attr("idBarang");
        var kodeBarang = $(this).attr("kodeBarang");
        $.ajax({
            url: 'barang/detail.php',
            method: 'post',
			data: {idBarang:idBarang},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("namaBarang").innerHTML='Detail Barang #'+kodeBarang;
            }
        });
        $('#modal').modal('show');
    });

    $('.btn-edit-barang').on('click',function(){
		var idBarang = $(this).attr("idBarang");
        var kodeBarang = $(this).attr("kodeBarang");
        $.ajax({
            url: 'barang/edit.php',
            method: 'post',
			data: {idBarang:idBarang},
            success:function(data){
                $('#tampil_data').html(data);  
                document.getElementById("namaBarang").innerHTML='Edit barang #'+kodeBarang;
            }
        });
        $('#modal').modal('show');
    });

    $('.btn-hapus').on('click',function(){
        konfirmasi=confirm("Yakin ingin menghapus barang ini?")
        if (konfirmasi){
            return true;
        }else {
            return false;
        }
    });
</script>