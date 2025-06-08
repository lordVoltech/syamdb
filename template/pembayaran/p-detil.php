<?php
include '../koneksi.php';

// =================================================================
// BAGIAN 1: Mengambil Data Induk Transaksi
// =================================================================

if (isset($_GET['no_transaksi'])) { // Changed from 'noservice' to 'no_transaksi'
    $no_transaksi = $_GET['no_transaksi']; // Changed from $noservice

    // Kueri disesuaikan: JOIN dari transaksi -> kendaraan -> customer untuk dapat nama customer
    // dan juga join dua kali ke tabel pegawai untuk mekanik dan kasir
    $query_transaksi = mysqli_query($conn, "SELECT 
                                            t.*, 
                                            k.merek,
                                            c.namacustomer, 
                                            pm.namapegawai AS nama_mekanik,
                                            pk.namapegawai AS nama_kasir
                                          FROM transaksi t
                                          LEFT JOIN kendaraan k ON t.no_pol = k.no_pol
                                          LEFT JOIN customer c ON k.idcustomer = c.idcustomer
                                          LEFT JOIN pegawai pm ON t.id_mekanik = pm.idpegawai
                                          LEFT JOIN pegawai pk ON t.id_kasir = pk.idpegawai
                                          WHERE t.no_transaksi = '$no_transaksi'"); // Changed from s.noservice

    if (mysqli_num_rows($query_transaksi) > 0) {
        $data_transaksi = mysqli_fetch_array($query_transaksi); // Changed from $data_service
    } else {
        die("Data Transaksi dengan nomor '$no_transaksi' tidak ditemukan."); // Changed error message
    }
} else {
    die("Nomor Transaksi tidak disediakan di URL."); // Changed error message
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>notadetail-lihat</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <form action="" method="post">
        <div class="wrapper d-flex align-items-stretch">
            <nav id="sidebar">
                <div class="p-4 pt-5">
                    <a href="#" class="img logo rounded-circle mb-5" style="background-image: url(../images/bengkel.png);"></a>
                    <ul class="list-unstyled components mb-5">
                        <li><a href="../home.php">Home</a></li>
                        <li><a href="../pelanggan/pelanggan-lihat.php">Pelanggan</a></li>
                        <li><a href="../pegawai/pegawai-lihat.php">Pegawai</a></li>
                        <li><a href="../barang/barang-lihat.php">Produk</a></li>
                        <li><a href="../Kendaraan/Kendaraan-lihat.php">Kendaraan</a></li>
                        <li class="active"><a href="#" data-toggle="collapse" aria-expanded="false">Transaksi</a></li>
                        <li><a href="../index.php" onclick="return confirm('yakin keluar?')">Logout</a></li>
                    </ul>
                    <div class="footer">
            <p>Narendra Aryo &copy;<script>
                document.write(new Date().getFullYear());
              </script> <br>   <i class="icon-heart" aria-hidden="true"></i>
              </p>
        </div>
                </div>
            </nav>

            <div id="content" class="p-4 p-md-5">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <button type="button" id="sidebarCollapse" class="btn btn-primary">
                            <i class="fa fa-bars"></i>
                            <span class="sr-only">Toggle Menu</span>
                        </button>
                        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa fa-bars"></i>
                            <i class="fa fa-bars"></i>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ml-auto">
                                <li class="nav-item"><a class="nav-link" href="../home.php">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="#pelanggan">Pelanggan</a></li>
                                <li class="nav-item"><a class="nav-link" href="../pegawai/pegawai-lihat.php">Pegawai</a></li>
                                <li class="nav-item"><a class="nav-link" href="../barang/barang-lihat.php">Produk</a></li>
                                <li class="nav-item"><a class="nav-link" href="../Kendaraan/Kendaraan-lihat.php">Kendaraan</a></li>
                                <li class="nav-item active"><a class="nav-link" href="#">Transaksi</a></li>
                                
                            </ul>
                        </div>
                    </div>
                </nav>

                <div class="form">
                    <label class="form-label" style="text-align: center; font-size: large; width:100%;">
                        DETAIL TRANSAKSI: <?php echo htmlspecialchars($data_transaksi['no_transaksi']); ?>
                    </label>
                    <hr>
                    <a class="btn btn-danger btn-sm" href="nota-lihat.php">Kembali</a>

                    <div class="form-element mt-3">
                        <label class="form-label" style="display: inline-block; width: 120px;">No. Transaksi</label>
                        <input class="form-control" value="<?php echo htmlspecialchars($data_transaksi['no_transaksi']); ?>" style="display: inline-block; width: calc(100% - 130px);" readonly>
                    </div>
                    <div class="form-element">
                        <label class="form-label" style="display: inline-block; width: 120px;">Tanggal</label>
                        <input class="form-control" value="<?php echo date('d-m-Y', strtotime($data_transaksi['tanggal_transaksi'])); ?>" style="display: inline-block; width: calc(100% - 130px);" readonly>
                    </div>
                    <div class="form-element">
                        <label class="form-label" style="display: inline-block; width: 120px;">No. Polisi</label>
                        <input class="form-control" value="<?php echo htmlspecialchars($data_transaksi['no_pol']); ?>" style="display: inline-block; width: calc(100% - 130px);" readonly>
                    </div>
                    <div class="form-element">
                        <label class="form-label" style="display: inline-block; width: 120px;">Nama Pelanggan</label>
                        <input class="form-control" value="<?php echo htmlspecialchars($data_transaksi['namacustomer']); ?>" style="display: inline-block; width: calc(100% - 130px);" readonly>
                    </div>
                    <div class="form-element">
                        <label class="form-label" style="display: inline-block; width: 120px;">Mekanik</label>
                        <input class="form-control" value="<?php echo htmlspecialchars($data_transaksi['nama_mekanik']); ?>" style="display: inline-block; width: calc(100% - 130px);" readonly>
                    </div>
                     <div class="form-element">
                        <label class="form-label" style="display: inline-block; width: 120px;">Kasir</label>
                        <input class="form-control" value="<?php echo htmlspecialchars($data_transaksi['nama_kasir']); ?>" style="display: inline-block; width: calc(100% - 130px);" readonly>
                    </div>
                    <div class="form-element">
                        <label class="form-label" style="display: inline-block; width: 120px;">KM Awal</label>
                        <input class="form-control" value="<?php echo htmlspecialchars($data_transaksi['km']); ?>" style="display: inline-block; width: calc(100% - 130px);" readonly>
                    </div>
                    <div class="form-element">
                        <label class="form-label" style="display: inline-block; width: 120px;">KM Berikut</label>
                        <input class="form-control" value="<?php echo htmlspecialchars($data_transaksi['km_berikut']); ?>" style="display: inline-block; width: calc(100% - 130px);" readonly>
                    </div>
                    <div class="form-element">
                        <label class="form-label" style="display: inline-block; width: 120px;">Service Berikut</label>
                        <input class="form-control" value="<?php echo date('d-m-Y', strtotime($data_transaksi['servis_berikut'])); ?>" style="display: inline-block; width: calc(100% - 130px);" readonly>
                    </div>
                    <?php if (isset($data_transaksi['perbaikan'])) { ?>
                    <div class="form-element">
                        <label class="form-label" style="display: inline-block; width: 120px;">Perbaikan</label>
                        <input class="form-control" value="<?php echo htmlspecialchars($data_transaksi['perbaikan']); ?>" style="display: inline-block; width: calc(100% - 130px);" readonly>
                    </div>
                    <?php } ?>
                </div>
                <br>

               <label class="form-label" style="text-align: center; font-size: large; width:100%;">DETAIL PRODUK & JASA</label>
                <hr>
                <div class="mb-3">
                    <a type="button" class="btn btn-success" href="notadetail-tambah.php?no_transaksi=<?php echo htmlspecialchars($data_transaksi['no_transaksi']); ?>">Tambah Produk/Jasa</a>
                    <a type="button" class="btn btn-warning" href="notacetak.php?no_transaksi=<?php echo htmlspecialchars($data_transaksi['no_transaksi']); ?>" style="color:white;">Cetak</a>
                </div>

                <table width='100%' border=1 style="text-align: center;">
                    
                    <thead>
                        <tr class="table-primary" style="color: black; font-weight: bold;">
                            <th width="5%">No</th>
                            <th width="10%">Qty</th>
                            <th>Nama Produk / Jasa</th>
                            <th width="15%">Harga Satuan</th>
                            <th width="15%">Jumlah</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        // Query untuk mengambil detail transaksi
                        $query_detail = mysqli_query($conn, "SELECT dt.*, p.idproduk, p.namaproduk, p.harga
                                                             FROM detail_transaksi dt
                                                             JOIN produk p ON dt.idproduk = p.idproduk
                                                             WHERE dt.no_transaksi = '$no_transaksi'"); // Changed from detail_service and noservice

                        $index = 1;
                        $total_sebelum_diskon = 0; // Initialize total before discount

                        // Check if no details found
                        if (mysqli_num_rows($query_detail) == 0) {
                            echo '<tr><td colspan="6">Belum ada produk/jasa yang ditambahkan.</td></tr>';
                        } else {
                            // Loop through details if found
                            while ($detail = mysqli_fetch_array($query_detail)) {
                                $jumlah = $detail['harga'] * $detail['banyaknya'];
                                $total_sebelum_diskon += $jumlah;
                        ?>
                                <tr>
                                    <td><?php echo $index++; ?></td>
                                    <td><?php echo htmlspecialchars($detail['banyaknya']); ?></td>
                                    <td style="text-align: left; padding-left: 10px;"><?php echo htmlspecialchars($detail['namaproduk']); ?></td>
                                    <td style="text-align: right; padding-right: 10px;">Rp <?php echo number_format($detail['harga']); ?></td>
                                    <td style="text-align: right; padding-right: 10px;">Rp <?php echo number_format($jumlah); ?></td>
                                    
                                    <td style="white-space: nowrap;">
                                        <a class="btn btn-primary btn-sm" href="notadetail-ubah.php?no_transaksi=<?php echo htmlspecialchars($no_transaksi); ?>&idproduk=<?php echo htmlspecialchars($detail['idproduk']); ?>">Ubah</a>
                                        <a class="btn btn-danger btn-sm" href="notadetail-hapus.php?no_transaksi=<?php echo htmlspecialchars($no_transaksi); ?>&idproduk=<?php echo htmlspecialchars($detail['idproduk']); ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
                                    </td>
                                </tr>
                        <?php 
                            } // End of while loop
                        } // End of else

                        // Get discount and calculate grand total
                        $diskon = $data_transaksi['diskon'];
                        $grand_total = $total_sebelum_diskon - $diskon;

                        // Update total and grand_total in the 'transaksi' table
                        $updateTransaksiQuery = "UPDATE transaksi SET total = '$total_sebelum_diskon', grand_total = '$grand_total' WHERE no_transaksi = '$no_transaksi'";
                        mysqli_query($conn, $updateTransaksiQuery);
                        ?>
                    </tbody>

                    <tfoot>
                        <tr style="font-weight: bold; background-color: #f2f2f2;">
                            <td colspan="4" style="text-align: right; padding-right:10px;"> TOTAL SEBELUM DISKON </td>
                            <td colspan="2" style="text-align: right; padding-right:10px;">Rp <?php echo number_format($total_sebelum_diskon); ?></td>
                        </tr>
                        <tr style="font-weight: bold; background-color: #f2f2f2;">
                            <td colspan="4" style="text-align: right; padding-right:10px;"> DISKON </td>
                            <td colspan="2" style="text-align: right; padding-right:10px;">Rp <?php echo number_format($diskon); ?></td>
                        </tr>
                        <tr style="font-weight: bold; background-color: #e0e0e0;">
                            <td colspan="4" style="text-align: right; padding-right:10px;"> GRAND TOTAL </td>
                            <td colspan="2" style="text-align: right; padding-right:10px;">Rp <?php echo number_format($grand_total); ?></td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>
    </form>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/popper.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>