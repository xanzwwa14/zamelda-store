<?php 
session_start();
if (isset($_POST['submit'])) {
    include '../../config/database.php';

    mysqli_query($connection, "START TRANSACTION");

    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $kodePelanggan = input($_POST["kodePelanggan"]);
    $username = input($_POST["username"]);
    $passwordInput = input($_POST["passwordPelanggan"]);

    $query_password = mysqli_query($connection, "SELECT passwordPelanggan FROM pelanggan WHERE kodePelanggan='$kodePelanggan' LIMIT 1");
    $data = mysqli_fetch_array($query_password);

    if ($data['passwordPelanggan'] == $passwordInput) {
        $password = $passwordInput;
    } else {
        $password = $passwordInput; // Bisa tambah hash kalau mau
    }

    $sql = "UPDATE pelanggan SET
        nama = '$username',
        passwordPelanggan = '$password'
        WHERE kodePelanggan = '$kodePelanggan'";

    $setting_pengguna = mysqli_query($connection, $sql);

    if ($setting_pengguna) {
        mysqli_query($connection, "COMMIT");
        header("Location: ../../index.php?page=pelanggan&setting-akun=berhasil");
    } else {
        mysqli_query($connection, "ROLLBACK");
        header("Location: ../../index.php?page=pelanggan&setting-akun=gagal");
    }
    exit;
}

$kodePelanggan = $_POST["kodePelanggan"];
include '../../config/database.php';
$query = mysqli_query($connection, "SELECT * FROM pelanggan WHERE kodePelanggan='$kodePelanggan'");
$data = mysqli_fetch_array($query);
$username = $data['nama'];
$password = $data['passwordPelanggan'];

if ($username == null) {
    echo "<div class='alert alert-warning'>nama dan passwordPelanggan belum di set up.</div>";
}
?>

<form action="pelanggan/setting-akun.php" method="post">
    <input name="kodePelanggan" value="<?php echo $kodePelanggan; ?>" type="hidden" class="form-control">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>nama:</label>
                <input name="nama" value="<?php echo $nama; ?>" id="nama" type="text" class="form-control" placeholder="Masukan nama" required>
                <div id="info_nama"></div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>passwordPelanggan:</label>
                <input name="passwordPelanggan" value="<?php echo $passwordPelanggan; ?>" type="passwordPelanggan" class="form-control" placeholder="Masukan passwordPelanggan" required>
            </div>
        </div>
    </div>
    <br>
    <button type="submit" name="submit" id="submit" class="btn btn-dark">Submit</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $("#nama").bind('keyup', function () {
        var nama = $('#nama').val();

        $.ajax({
            url: 'pelanggan/cek-nama.php',
            method: 'POST',
            data: {nama: nama},
            success: function(data) {
                $('#info_nama').show();
                $('#info_nama').html(data);
            }
        }); 
    });
</script>
