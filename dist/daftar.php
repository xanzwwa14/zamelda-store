<?php
session_start();

abstract class DataHandler {
    protected $kon;

    public function __construct($kon) {
        $this->kon = $kon;
    }

    abstract public function save($data);
}

// class FileHandler {
//     private $file_tmp;
//     private $file_name;

//     public function __construct($file_tmp, $file_name) {
//         $this->file_tmp = $file_tmp;
//         $this->file_name = $file_name;
//     }

//     public function saveFile($destination) {
//         return move_uploaded_file($this->file_tmp, $destination . $this->file_name);
//     }

//     public function getFileName() {
//         return $this->file_name;
//     }
// }

class pelangganHandler extends DataHandler {
    public function save($data) {
        $kodePelanggan = $data['kodePelanggan'];
        $namaPelanggan = $data['namaPelanggan'];
        $noTelp = $data['noTelp'];
        $email = $data['email'];
        $alamat = $data['alamat'];
        $foto_name = 'foto_default.png'; 
        $status = 1;

    //    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    //         $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');
    //         $foto = $_FILES['foto']['name'];
    //         $x = explode('.', $foto);
    //         $ekstensi = strtolower(end($x));
    //         $file_tmp = $_FILES['foto']['tmp_name'];
        
    //         if (in_array($ekstensi, $ekstensi_diperbolehkan)) {
    //             $unique_name = uniqid() . '.' . $ekstensi;
    //             $fileHandler = new FileHandler($file_tmp, $unique_name);
    //             if ($fileHandler->saveFile('../pelanggan/foto/')) {
    //                 $foto_name = $fileHandler->getFileName(); 
    //             }
    //         }
    //     } else {
    //         $foto_name = 'foto_default.png';
    //     }


        return mysqli_query($this->kon, "INSERT INTO pelanggan (kodePelanggan, namaPelanggan, noTelp, email, alamat, foto) 
                                         VALUES ('$kodePelanggan', '$namaPelanggan', '$noTelp', '$email', '$alamat', '$foto_name')");
    }
}

class PenggunaHandler extends DataHandler {
    public function save($data) {
        $kodePengguna = $data['kodePengguna'];
        $username = $data['username'];
        $password = $data['password']; 
        $status = $data['status'];
        $level = $data['level'];

        return mysqli_query($this->kon, "INSERT INTO pengguna (kodePengguna, username, password, status, level) 
                                         VALUES ('$kodePengguna', '$username', '$password', '$status', '$level')");
    }
}

function input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include "../config/database.php";
    mysqli_query($kon, "START TRANSACTION");

    $query = mysqli_query($kon, "SELECT MAX(idPelanggan) as kodeTerbesar FROM pelanggan");
    $data = mysqli_fetch_array($query);
    $idPelanggan = $data['kodeTerbesar'] + 1;
    $kodePelanggan = "plg" . sprintf("%03s", $idPelanggan);

    $namaPelanggan = input($_POST["nama"]);
    $noTelp = input($_POST["noTelp"]);
    $email = input($_POST["email"]);
    $alamat = input($_POST["alamat"]);
    $username = input($_POST["username"]);
    $password = input($_POST["password"]); 

    $pelangganData = [
        'kodePelanggan' => $kodePelanggan,
        'namaPelanggan' => $namaPelanggan,
        'noTelp' => $noTelp,
        'email' => $email,
        'alamat' => $alamat
    ];

    $penggunaData = [
        'kodePengguna' => $kodePelanggan,
        'username' => $username,
        'password' => $password,
        'status' => 1,
        'level' => "Pelanggan"
    ];

    $pelangganHandler = new pelangganHandler($kon);
    $penggunaHandler = new PenggunaHandler($kon);

    $simpan_pelanggan = $pelangganHandler->save($pelangganData);
    $simpan_pengguna = $penggunaHandler->save($penggunaData);

    if ($simpan_pelanggan && $simpan_pengguna) {
        mysqli_query($kon, "COMMIT");
        header("Location: login.php?daftar=berhasil");
        exit;
    } else {
        mysqli_query($kon, "ROLLBACK");
        header("Location: daftar.php?daftar=gagal");
        exit;
    }
}
?>


<?php 
include '../config/database.php';
$hasil=mysqli_query($kon,"select * from profil_aplikasi order by nama_aplikasi desc limit 1");
$data = mysqli_fetch_array($hasil); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title><?php echo $data['nama_aplikasi'];?></title>
    <link href="../src/templates/css/styles.css" rel="stylesheet" />
</head>
<body class="bg-dark">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Buat Akun Baru</h3></div>
                <div class="card-body">
                    <?php 
                    if (isset($_GET['daftar']) && $_GET['daftar'] == 'gagal'){
                        echo "<div class='alert alert-warning'><strong>Gagal!</strong> Pendaftaran akun gagal.</div>";
                    }   
                    ?>
                    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <label>Nama</label>
                                <input class="form-control" name="nama" type="text" required />
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Nomor Telp</label>
                                <input class="form-control" name="noTelp" type="text" required />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <label>Email</label>
                                <input class="form-control" name="email" type="email" required />
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Alamat</label>
                                <input class="form-control" name="alamat" type="text" required />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <label>Username</label>
                                <input class="form-control" name="username" id="username" type="text" required />
                                <div id="info_username"></div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Password</label>
                                <input class="form-control" name="password" type="password" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Upload Foto (Opsional)</label>
                            <input class="form-control-file" name="foto" type="file" accept=".jpg,.jpeg,.png" />
                        </div>
                        <div class="form-group mt-4 mb-0">
                            <button class="btn btn-warning btn-block" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <div class="small"><a href="login.php">Sudah punya akun? Login!</a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="src/js/jquery/jquery-3.5.1.min.js"></script>
<script>
$("#username").keyup(function () {
    var username = $('#username').val();
    $.ajax({
        url: 'pelanggan/cek-username.php',
        method: 'POST',
        data: {username: username},
        success: function(data) {
            $('#info_username').html(data);
        }
    });
});
</script>
</body>
</html>
