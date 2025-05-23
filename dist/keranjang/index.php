<?php
// session_start();
include '../config/database.php';
?>

<script>
    $('title').text('Keranjang Barang');
</script>

<main>
    <div class="container-fluid">
        <h2 class="mt-4">Keranjang</h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Keranjang</li>
        </ol>

        <?php
        if (isset($_GET['kodeBarang']) && isset($_GET['kodeVarian'])) {
            $kodeBarang = $_GET['kodeBarang'];
            $kodeVarian = $_GET['kodeVarian'];

            $query = mysqli_query($kon, "
                SELECT * FROM barang 
                INNER JOIN varianbarang 
                ON barang.kodeBarang = varianbarang.kodeBarang 
                WHERE barang.kodeBarang='$kodeBarang' AND varianbarang.kodeVarian='$kodeVarian'
            ");
            $data = mysqli_fetch_array($query);

            $itemArray = array(
                $kodeVarian => array(
                    'kodeBarang' => $data['kodeBarang'],
                    'kodeVarian' => $data['kodeVarian'],
                    'namaBarang' => $data['namaBarang'],
                    'typeVarian' => $data['typeVarian'],
                    'size' => $data['size'],
                    'harga' => $data['harga'],
                    'jumlah' => 1
                )
            );

            if (!empty($_SESSION["cart_barang"])) {
                if (array_key_exists($kodeVarian, $_SESSION["cart_barang"])) {
                    $_SESSION["cart_barang"][$kodeVarian]['jumlah'] += 1;
                } else {
                    $_SESSION["cart_barang"] = array_merge($_SESSION["cart_barang"], $itemArray);
                }
            } else {
                $_SESSION["cart_barang"] = $itemArray;
            }
        }

        if (isset($_GET['aksi']) && $_GET['aksi'] == "hapus_barang" && isset($_GET['kodeVarian'])) {
            $kodeVarian = $_GET['kodeVarian'];
            unset($_SESSION["cart_barang"][$kodeVarian]);
        }
        ?>

        <div class="mb-3">
            <a href="index.php?page=barang" class="btn btn-dark">Pilih Barang</a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Varian</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 0;
                        $total_semua = 0;
                        if (!empty($_SESSION["cart_barang"])) :
                            foreach ($_SESSION["cart_barang"] as $item) :
                                $no++;
                                $total = $item['harga'] * $item['jumlah'];
                                $total_semua += $total;
                        ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $item['namaBarang']; ?></td>
                                    <td><?php echo $item['typeVarian'] . ' / ' . ($item['size'] ?? '-'); ?></td>
                                    <td>Rp<?php echo number_format($item['harga'] ?? 0, 0, ',', '.'); ?></td>
                                    <td><?php echo $item['jumlah']; ?></td>
                                    <td>Rp<?php echo number_format($total, 0, ',', '.'); ?></td>
                                    <td>
                                        <a href="index.php?page=keranjang&kodeVarian=<?php echo $item['kodeVarian']; ?>&aksi=hapus_barang" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                        <?php
                            endforeach;
                        else :
                            echo '<tr><td colspan="7" class="text-center">Keranjang masih kosong.</td></tr>';
                        endif;
                        ?>
                    </tbody>
                    <?php if (!empty($_SESSION["cart_barang"])): ?>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right">Total Semua</th>
                            <th colspan="2">Rp<?php echo number_format($total_semua, 0, ',', '.'); ?></th>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <div class="text-right">
            <?php if (!empty($_SESSION["cart_barang"])): ?>
                <a href="keranjang/submit.php" id="ajukan" class="btn btn-success">Lanjutkan Transaksi</a>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
    $('#ajukan').on('click', function () {
        return confirm("Apakah anda yakin ingin mengajukan transaksi barang ini?");
    });
</script>
