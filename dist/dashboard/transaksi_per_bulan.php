<?php
session_start();
?>

<?php
class StaticDashboardData {
  private $totalTransaksi;
  private $jumlahPelanggan;
  private $jumlahBarang;

  public function __construct() {
      $this->totalTransaksi = 10;
      $this->jumlahPelanggan = 20;
      $this->jumlahBarang = 30;
  }

  public function getTotalTransaksi() {
      return $this->totalTransaksi;
  }

  public function getJumlahPelanggan() {
      return $this->jumlahPelanggan;
  }

  public function getJumlahBarang() {
      return $this->jumlahBarang;
  }
}

interface DashboardInterface {
  public function getTotalTransaksi();
  public function getJumlahPelanggan();
  public function getJumlahBarang();
}
?>


<canvas id="transaksi_per_bulan" width="100%" height="60"></canvas>
<?php
    include '../../config/database.php';
    $tahun=date('Y');

    $label = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];

    for($bulan = 1;$bulan <= 12;$bulan++)
    {
      if ($_SESSION["level"]=='Anggota' or $_SESSION["level"]=='anggota'){

        $kodepelanggan=$_SESSION["kodePengguna"];
        $sql="select MONTH(tglTransaksi) as bulan,count(*) as total from detail_transaksi d
        inner join transaksi p on p.kodeTransaksi=d.kodeTransaksi
        where MONTH(tglTransaksi)='$bulan' and YEAR(tglTransaksi)='$tahun' and p.kodePelanggan='$kodePelanggan'
        group by bulan";
      }else {
        $sql="select MONTH(tglTransaksi) as bulan,count(*) as total from detail_transaksi d
        inner join transaksi p on p.kodeTransaksi=d.kodeTransaksi
        where MONTH(tglTransaksi)='$bulan' and YEAR(tglTransaksi)='$tahun'
        group by bulan";
      }
    
        $hasil=mysqli_query($kon,$sql);
        $data=mysqli_fetch_array($hasil);
        
        if (isset($data['total'])!=0){
            $total[] = $data['total'];
        }else {
            $total[] = 0;
        }
    }
?>

<script>
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Bar Chart
var ctx = document.getElementById("transaksi_per_bulan");
var myLineChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($label); ?>,
    datasets: [{
      label: "Jumlah Pustaka",
      backgroundColor: "rgba(2,117,216,1)",
      borderColor: "rgba(2,117,216,1)",
      data:  <?php echo json_encode($total); ?>,
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'month'
        },
        gridLines: {
          display: false
        },
        ticks: {
          maxTicksLimit: 16
        }
      }],
      yAxes: [{
        ticks: {
          min: 0,
          maxTicksLimit: 12
        },
        gridLines: {
          display: true
        }
      }],
    },
    legend: {
      display: false
    }
  }
});
</script>

