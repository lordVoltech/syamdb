<?php
include '../koneksi.php';

// =================================================================
// BAGIAN 1: Mengambil Data Induk Transaksi
// =================================================================

if (isset($_GET['no_transaksi'])) { // Changed from 'noservice' to 'no_transaksi'
    $no_transaksi = $_GET['no_transaksi']; // Changed from $noservice

    // Kueri disesuaikan: JOIN dari transaksi -> motor -> customer untuk dapat nama customer
    // dan juga join dua kali ke tabel pegawai untuk mekanik dan kasir
    $query_transaksi = mysqli_query($conn, "SELECT 
                                            t.*, 
                                            k.jenis,
                                            c.nama_cos, 
                                            pm.nama AS nama_mekanik,
                                            pk.nama AS nama_kasir
                                          FROM pembayaran t
                                          LEFT JOIN motor k ON t.no_pol = k.no_pol
                                          LEFT JOIN costomer c ON k.id_cos = c.id_cos
                                          LEFT JOIN pegawai pm ON t.id_mekanik = pm.id_pegawai
                                          LEFT JOIN pegawai pk ON t.id_kasir = pk.id_pegawai
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
<body>
    <form action="" method="post">
        
            <div id="content" class="p-4 p-md-5">
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
                        <input class="form-control" value="<?php echo htmlspecialchars($data_transaksi['nama_cos']); ?>" style="display: inline-block; width: calc(100% - 130px);" readonly>
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
                    <a type="button" class="btn btn-success" href="p-tambah.php?no_transaksi=<?php echo htmlspecialchars($data_transaksi['no_transaksi']); ?>">Tambah Produk/Jasa</a>
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
                        // Query untuk mengambil detail pembayaran
                        $query_detail = mysqli_query($conn, "SELECT dt.*, p.idproduk, p.nama_barang_jasa, p.harga
                                                             FROM detail_pembayaran dt
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
                                    <td style="text-align: left; padding-left: 10px;"><?php echo htmlspecialchars($detail['nama_barang_jasa']); ?></td>
                                    <td style="text-align: right; padding-right: 10px;">Rp <?php echo number_format($detail['harga']); ?></td>
                                    <td style="text-align: right; padding-right: 10px;">Rp <?php echo number_format($jumlah); ?></td>
                                    
                                    <td style="white-space: nowrap;">
                                        <a class="btn btn-primary btn-sm" href="p-detailubah.php?no_transaksi=<?php echo htmlspecialchars($no_transaksi); ?>&idproduk=<?php echo htmlspecialchars($detail['idproduk']); ?>">Ubah</a>
                                        <a class="btn btn-danger btn-sm" href="p-detailhapus.php?no_transaksi=<?php echo htmlspecialchars($no_transaksi); ?>&idproduk=<?php echo htmlspecialchars($detail['idproduk']); ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
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