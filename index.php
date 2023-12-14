<?php
// $dbHost = "localhost";
// $dbDatabase = "whsakila2021";
// $dbUser = "root";
// $dbPasswrod = "";

// $mysqli = mysqli_connect($dbHost, $dbUser, $dbPasswrod, $dbDatabase);

$dbHost = "localhost";
$dbDatabase = "newadventurework";
$dbUser = "root";
$dbPasswrod = "";

$mysqli = mysqli_connect($dbHost, $dbUser, $dbPasswrod, $dbDatabase);

// Periksa koneksi
if ($mysqli->connect_error) {
  die("Koneksi ke database gagal: " . $mysqli->connect_error);
}

// Fakta_Permintaan
$query = "SELECT t.tahun, v.Name AS NamaVendor, CONCAT(ROUND((AVG(fp.OnOrderQty) * 100), 2), '%') AS PersentaseRataRataQty
FROM fakta_permintaan fp
JOIN vendor v ON fp.VendorID = v.VendorID
JOIN time t ON fp.time_id = t.time_id
GROUP BY t.tahun, v.Name
HAVING PersentaseRataRataQty IS NOT NULL";

$result = $mysqli->query($query);

// Menyimpan data dalam array
$data = array();
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}
$data_json = json_encode($data);

// Fakta_Pembelian
$query2 = "SELECT sm.Name AS NamaShipMethod, COUNT(fp.ShipMethodID) AS Jumlah
FROM fakta_pembelian fp
JOIN shipmethod sm ON fp.ShipMethodID = sm.ShipMethodID
JOIN purchasing p ON fp.PurchaseOrderID = p.PurchaseOrderID
WHERE p.Status = 4
GROUP BY fp.ShipMethodID, sm.Name;";

$result2 = $mysqli->query($query2);

// Menyimpan data dalam array
$data2 = array();
while ($row2 = $result2->fetch_assoc()) {
  $data2[] = $row2;
}
$data2_json = json_encode($data2);

//PERSIAPAN DASHBOARD ATAS (KOTAK)
//1. Total Product
$sql2 = "SELECT count(distinct Name) as jml_product from product";
$jml_p = mysqli_query($mysqli, $sql2);
$jml_product = mysqli_fetch_assoc($jml_p);

//2. Total Order Pembelian
$sql3 = "SELECT count(distinct PurchaseOrderID) as jml_order from fakta_pembelian";
$jml_o = mysqli_query($mysqli, $sql3);
$jml_order = mysqli_fetch_assoc($jml_o);

//3. Total Sales Permintaan
$sql4 = "SELECT sum(OnOrderQty) as order_permintaan from fakta_permintaan";
$jml_pt = mysqli_query($mysqli, $sql4);
$order_permintaan = mysqli_fetch_assoc($jml_pt);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/data.js"></script>
  <script src="https://code.highcharts.com/modules/variable-pie.js"></script>
  <script src="https://code.highcharts.com/modules/drilldown.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="/drilldown.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js">
  </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> -->
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
          <a class="nav-link" data-widget="navbar-search" href="#" role="button">
            <i class="fas fa-search"></i>
          </a>
        </li>

        <!-- Messages Dropdown Menu -->
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- End navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image" style="display: flex; align-items: center;">
            <img src="dist/img/logoupnbaru.png" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">DATAWAREHOUSE PAR-C</a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item menu-open">
              <a href="#" class="nav-link active d-flex align-items-center">
                <i class="nav-icon fas fa-user mr-3"></i>
                <p>Anggota Kelompok 8
                  <i class="right fas fa-angle-left mt-2"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="./index.php" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>21082010115 - Ardyanto W.S</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./index.php" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>21082010099 - Divani Jane</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./index.php" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>21082010106 - Ferdi Pugoh</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="./index.php" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>21082010121 - Lintang Fr</p>
                  </a>
                </li>
              </ul>
            </li>  
          </ul>    
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
    <!-- End Main Sidebar Container -->

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <!-- Content Header -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard v1</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- End content-header -->

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3 style="margin-top: 15px;"><?php echo $jml_product['jml_product']; ?></h3>
                  <p>
                  <h3>Total Product</h3>
                  </p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>

                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3 style="margin-top: 15px;"> <?php echo number_format($jml_order['jml_order'], 0, ',', '.'); ?></h3>
                  <p>
                  <h3>Total Pembelian</h3>
                  </p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3 style="color: white; margin-top: 15px;"> <?php echo number_format($order_permintaan['order_permintaan'], 0, ',', '.'); ?></h3>
                  <p>
                  <h3 style="color: white;">Total OnOrderQty</h3>
                  </p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
              </div>
            </div>
          </div>
          <!-- End Small boxes (Stat box) -->

          <!-- Main row -->
          <div class="row mt-5">
            <!-- Left col -->
            <div class="col-lg-12" id="permintaan">
              <script>
                // Data hasil query
                var data = <?php echo $data_json; ?>

                // Mengubah menjadi format Highcharts
                var seriesData = [];
                var categories = [];
                var vendors = [];

                data.forEach(function(item){
                  var tahun = item.tahun.toString();
                  var vendor = item.NamaVendor;
                  var persentase = parseFloat(item.PersentaseRataRataQty);

                  // Memasukkan nama vendor ke objek vendors
                  if(!vendors.hasOwnProperty(vendor)){
                    vendors[vendor] = [];
                  }
                  vendors[vendor].push(persentase);

                  // memastikan tahun hanya dimasukkan satu kali ke dalam categories
                  if(!categories.includes(tahun)){
                    categories.push(tahun);
                  }
                });

                // Mengubah objek vendors menjadi array seriesData
                for(var vendor in vendors){
                  seriesData.push({
                    name: vendor,
                    data: vendors[vendor]
                  });
                }

                // Membuat grafik menggunakan Highcharts
                Highcharts.chart('permintaan', {
                  chart: {
                    type: 'column'
                  },
                  title: {
                    text: 'Grafik Rata-Rata OnOrderQty'
                  },
                  xAxis: {
                    categories: categories,
                    crosshair: true
                  },
                  yAxis: {
                    title: {
                      text: 'Rata-Rata'
                    }
                  },
                  series: seriesData
                })
              </script>
            </div>
            <!-- End Left col -->

            <!-- right col -->
            <div class="col-lg-12 connectedSortable">
              <!-- Custom tabs (Charts with tabs)-->
              <div>
                <div class="card-body">
                  <div class="tab-content p-0">
                    <!-- Morris chart - Sales -->
                    <div id="revenue-chart" style="position: relative; height: 300px; width: 100%;">
                      <!-- <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas> -->
                      <figure class="highcharts-figure">
                        <div>
                          <iframe name="mondrian" src="http://localhost:8081/mondrian/index.html" style="height: 300px; width:100%; border:none; text-align:center;""></iframe> 
                        </div>
                      </figure>
                    </div>
                  </div>
                </div><!-- /.card-body -->
              </div>
            </div>
            <!-- End right col -->
            
            <!-- Fakta Pembelian -->
            <div class="col-lg-12" id="fakta_pembelian">
              <script>  
                // Data hasil query
                var data = <?php echo $data2_json; ?>;

                // Mengubah data menjadi format yang diterima oleh Highcharts
                var pieData = [];

                data.forEach(function(item) {
                var namaShipMethod = item.NamaShipMethod;
                var jumlah = parseInt(item.Jumlah);

                  // Memasukkan data ke dalam array pieData
                  pieData.push({
                    name: namaShipMethod,
                    y: jumlah
                  });
                });

                // Membuat grafik menggunakan Highcharts
                Highcharts.chart('fakta_pembelian', {
                  chart: {
                    type: 'pie'
                  },
                  title: {
                    text: 'Grafik Jumlah Banyaknya Ship Method'
                  },
                  series: [{
                    name: 'Jumlah',
                    data: pieData
                  }]
                });
              </script>
            </div>
             <!-- End Fakta Pembelian -->
          </div>
      </section>
      <!-- End Main Contentt -->
    </div>
    <!-- End Content Wrapper -->
  </div>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- ChartJS -->
  <script src="plugins/chart.js/Chart.min.js"></script>
  <!-- Sparkline -->
  <script src="plugins/sparklines/sparkline.js"></script>
  <!-- JQVMap -->
  <script src="plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <script src="plugins/moment/moment.min.js"></script>
  <script src="plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="dist/js/demo.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="dist/js/pages/dashboard.js"></script>

</body>

</html>