<div class="row">
    <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
            <div class="card-body">
<form>
<h4 Style="text-align:center">TABEL Pembayaran</h4>
<a class="badge badge-success" href="index.php?folder=pembayaran&page=p-tambah">Tambah</a>
<!-- <a class="kembali" href="../menu.php">Kembali</a> -->
<table class="table table-stripped" width="100%">
    <thead>

        <tr>
            <th>Nomor Transaksi</th>
            <th>nomor polisi</th>
            <th>mekanik</th>
            <th>kasir</th>
            <th>Tanggal</th>
        <th>km</th>
        <th>grand total</th>
        
        <th>Aksi</th>
    </tr>
</thead>
    <tr>
        <?php
            include 'koneksi.php';
            $limit = 10; // Jumlah baris per halaman
            // $page = isset($_GET['page']) ? $_GET['page'] : 1;
            // $start = ($page - 1) * $limit;
        
            $query = mysqli_query($conn, "SELECT pembayaran.*, kasir.nama as nama_kasir, mekanik.nama as nama_mekanik FROM pembayaran JOIN pegawai AS mekanik ON pembayaran.id_mekanik = mekanik.id_pegawai JOIN pegawai AS kasir ON pembayaran.id_kasir = kasir.id_pegawai");
            while ($data=mysqli_fetch_array($query)){
                ?>
                <tr>
                    <td><?php echo $data['no_transaksi'] ;?></td>
                    <td><?php echo $data['no_pol'] ;?></td>
                    <td><?php echo $data['nama_mekanik'] ;?></td>
                    <td><?php echo $data['nama_kasir'] ;?></td>
                    <td><?php echo $data['tanggal_transaksi'] ;?></td>
                    <td><?php echo $data['km'] ;?></td>
                    <td><?php echo $data['grand_total'] ;?></td>
                    <td>
                    <a class="badge badge-primary" href="index.php?folder=pembayaran&page=p-ubah&no_transaksi=<?php echo $data['no_transaksi'];?>" >Edit</a><br><br>
                    <a class="badge badge-danger" href="pembayaran/p-hapus.php?no_transaksi=<?php echo $data['no_transaksi']; ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>				
                    </td>
                </tr>
                </tr>
        <?php } ?>

</table>
    <!-- <div class="pagination">
            <?php
            $query_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM pembayaran");
            $data_total = mysqli_fetch_assoc($query_total);
            $total_pages = ceil($data_total['total'] / $limit);

            if ($page > 1) {
                echo '<a href="?page=' . ($page - 1) . '">Back</a>';
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo '<a href="?page=' . $i . '" class="current">' . $i . '</a>';
                } else {
                    echo '<a href="?page=' . $i . '">' . $i . '</a>';
                }
            }

            if ($page < $total_pages) {
                echo '<a href="?page=' . ($page + 1) . '">Next</a>';
            }
            ?>
        </div> -->
</form>
  
        </div>
        </div>
        </div>
        </div>