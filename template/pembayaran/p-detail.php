<?php
    include 'koneksi.php';
    $query = mysqli_query($conn, "Select*from pembayaran where no_transaksi = '$_GET[no_transaksi]'");
    $data = mysqli_fetch_array($query);
    
    $no_transaksi = $_GET['no_transaksi'];
    $query_barang_jasa = mysqli_query($conn, "SELECT dp.id_barang_jasa, b.nama_barang_jasa, dp.qty, dp.sub_total
                                                FROM detail_pembayaran dp
                                                JOIN barang_jasa b ON dp.id_barang_jasa = b.id_barang_jasa
                                                WHERE dp.no_transaksi = '$_GET[no_transaksi]'");
    $barang_jasa_details = [];
    while ($row = mysqli_fetch_assoc($query_barang_jasa)) {
        $barang_jasa_details[] = $row;
    }

?>
  
<html>
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-body">
                
                <h4 class="text-center">Pembayaran</h4>
                <div class="text-right mb-3"> <a href="index.php?folder=pembayaran&page=p-cetak-pdf&no_transaksi=<?php echo htmlspecialchars($no_transaksi); ?>"
                       class="btn btn-info btn-sm" target="_blank">Cetak Struk PDF</a>
                </div>

                <form action="" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor Transaksi</label>
                                <p class="form-control-static"><?php echo htmlspecialchars($data['no_transaksi']); ?></p>
                            </div>

                            <div class="form-group">
                                <label>Tanggal Hari Ini</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(date('d-m-Y', strtotime($data['tanggal_transaksi']))); ?></p>
                            </div>

                            <div class="form-group">
                                <label>Tanggal Servis Berikutnya</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(date('d-m-Y', strtotime($data['servis_berikut']))); ?></p>
                            </div>

                            <div class="form-group">
                                <label>KM Saat Ini</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(number_format($data['km'])); ?></p>
                            </div>

                            <div class="form-group">
                                <label>KM Maintenance</label>
                                <p class="form-control-static"><?php echo htmlspecialchars(number_format($data['km_berikut'])); ?></p>
                            </div>

                            <div class="form-group">
                                <label>Total</label>
                                <p class="form-control-static">Rp <?php echo htmlspecialchars(number_format($data['total'])); ?></p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Diskon</label>
                                <p class="form-control-static">Rp <?php echo htmlspecialchars(number_format($data['diskon'])); ?></p>
                            </div>

                            <div class="form-group">
                                <label>Grand Total</label>
                                <p class="form-control-static">Rp <?php echo htmlspecialchars(number_format($data['grand_total'])); ?></p>
                            </div>

                            <div class="form-group">
                                <label>Nopol</label>
                                <p class="form-control-static">
                                    <?php
                                    echo htmlspecialchars($data['no_pol']);
                                    ?>
                                </p>
                            </div>

                            <div class="form-group">
                                <label>Mekanik</label>
                                <p class="form-control-static">
                                    <?php
                                    $mechanic_name = 'N/A';
                                    if (isset($conn) && isset($data['id_mekanik'])) {
                                        $query_mekanik = mysqli_query($conn, "SELECT nama FROM pegawai WHERE id_pegawai = '" . mysqli_real_escape_string($conn, $data['id_mekanik']) . "' LIMIT 1");
                                        if ($mekanik_row = mysqli_fetch_array($query_mekanik)) {
                                            $mechanic_name = $mekanik_row['nama'];
                                        }
                                    }
                                    echo htmlspecialchars($mechanic_name);
                                    ?>
                                </p>
                            </div>

                            <div class="form-group">
                                <label>Kasir</label>
                                <p class="form-control-static">
                                    <?php
                                    // Similar to Mekanik, fetch Kasir's name
                                    $kasir_name = 'N/A';
                                    if (isset($conn) && isset($data['id_kasir'])) {
                                        $query_kasir = mysqli_query($conn, "SELECT nama FROM pegawai WHERE id_pegawai = '" . mysqli_real_escape_string($conn, $data['id_kasir']) . "' LIMIT 1");
                                        if ($kasir_row = mysqli_fetch_array($query_kasir)) {
                                            $kasir_name = $kasir_row['nama'];
                                        }
                                    }
                                    echo htmlspecialchars($kasir_name);
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="text-left mt-4">
                        <a class="badge badge-danger" href="index.php?folder=pembayaran&page=p-lihat">Kembali</a>
                        </div>
                </form>

                <h4 class="text-center mb-4">DETAIL BARANG DAN JASA</h4>
                    <div class="text-left mb-3">
                        <a class="badge badge-primary" href="index.php?folder=pembayaran&page=p-detailtambah&no_transaksi=<?php echo htmlspecialchars($no_transaksi); ?>">Tambah Item</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <thead>
                                <tr class="table-primary">
                                    <th style="width: 30%;">Nama Barang Jasa</th>
                                    <th style="width: 20%;">Qty</th>
                                    <th style="width: 30%;">Sub Total</th>
                                    <th style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($barang_jasa_details)): ?>
                                    <tr>
                                        <td colspan="3">Tidak ada data barang/jasa tambahan.</td>
                                        </tr>
                                <?php else: ?>
                                    <?php foreach ($barang_jasa_details as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['nama_barang_jasa']); ?></td>
                                            <td><?php echo htmlspecialchars($item['qty']); ?></td>
                                            <td>Rp <?php echo htmlspecialchars(number_format($item['sub_total'], 0, ',', '.')); ?></td>
                                            <td>
                                                <a href="index.php?folder=pembayaran&page=p-detailedit&no_transaksi=<?php echo htmlspecialchars($no_transaksi); ?>&id_barang_jasa=<?php echo htmlspecialchars($item['id_barang_jasa']); ?>"
                                                   class="btn btn-warning btn-sm mr-1">Edit</a>
                                                <a href="index.php?folder=pembayaran&page=p-detailhapus&no_transaksi=<?php echo htmlspecialchars($no_transaksi); ?>&id_barang_jasa=<?php echo htmlspecialchars($item['id_barang_jasa']); ?>"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?');">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>


            </div>
        </div>
    </div>
</div>

</html>