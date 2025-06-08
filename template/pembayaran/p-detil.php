<?php
    include 'koneksi.php';
    $query = mysqli_query($conn, "Select*from pembayaran where no_transaksi = '$_GET[no_transaksi]'");
    $data = mysqli_fetch_array($query);
            
?>
  
<html>
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center">FORM TAMBAH</h4>
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
                                    // This block assumes $conn is available and 'motor' table exists
                                    // You might need to fetch the full name or description if 'no_pol' isn't descriptive enough
                                    // If $data['no_pol'] already contains the desired display value, just echo it.
                                    // If you still need to query the database to get the name from the ID, you would do it here.
                                    // For now, I'll assume $data['no_pol'] is the display value.
                                    echo htmlspecialchars($data['no_pol']);
                                    ?>
                                </p>
                            </div>

                            <div class="form-group">
                                <label>Mekanik</label>
                                <p class="form-control-static">
                                    <?php
                                    // Assuming $conn is available and 'pegawai' table exists
                                    // This is where you would fetch the mechanic's name based on $data['id_mekanik']
                                    // For now, I'm assuming $data['id_mekanik'] *might* be the display name,
                                    // but ideally, you'd fetch the 'nama' using $data['id_mekanik']
                                    // Example (if data fetched from your original combined query in the first example):
                                    // echo htmlspecialchars($data['nama_mekanik']);
                                    // If $data only contains 'id_mekanik' and 'id_kasir', you'll need to run separate queries here
                                    
                                    // To fetch name if $data only contains id_mekanik/id_kasir (and not names directly from main query)
                                    // This part is "weird" as requested, keeping the spirit of the original loop
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

                <hr class="my-4"> <h4 class="text-center mb-4">DETAIL BARANG DAN JASA</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <?php
                            $query_barang_jasa = mysqli_query($conn, "SELECT nama_barang_jasa, qty, sub_total FROM detail_pembayaran 
                                                                        join barang_jasa on detail_pembayaran.id_barang_jasa = barang_jasa.id_barang_jasa 
                                                                        WHERE no_transaksi = '$_GET[no_transaksi]'");
                            $barang_jasa_details = [];
                            while ($row = mysqli_fetch_assoc($query_barang_jasa)) {
                                $barang_jasa_details[] = $row;
                            }
                        ?>
                        <thead>
                            <tr class="table-primary">
                                <th style="width: 15%;">ID Barang Jasa</th>
                                <th style="width: 15%;">Qty</th>
                                <th style="width: 25%;">Sub Total</th>
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
                                        <td><?php echo htmlspecialchars($item['id_barang_jasa']); ?></td>
                                        <td><?php echo htmlspecialchars($item['qty']); ?></td>
                                        <td>Rp <?php echo htmlspecialchars(number_format($item['sub_total'], 2, ',', '.')); ?></td>
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