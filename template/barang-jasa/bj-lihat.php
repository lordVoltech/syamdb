<html>
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
            <div class="card-body">
            <h3 style="text-align : center;"> Data Barang/Jasa </h3>
<form>
<a class="badge badge-success" href="index.php?folder=barang-jasa&page=bj-tambah">Tambah</a>
<!-- <a class="kembali" href="../menu.php">Kembali</a> -->

<table class="table">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Harga</th>
        <th>Jenis</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>
    <tr>
        <?php
            include 'koneksi.php';
            $limit = 10; // Jumlah baris per halaman
            $g = isset($_GET['g']) ? $_GET['g'] : 1;
            $start = ($g - 1) * $limit;
        
            $query = mysqli_query($conn, "SELECT * FROM barang_jasa LIMIT $start, $limit");
            while ($data=mysqli_fetch_array($query)){
                ?>
                <tr>
                    <td><?php echo $data['id_barang_jasa'] ;?></td>
                    <td><?php echo $data['nama_barang_jasa'] ;?></td>
                    <td><?php echo $data['harga_satuan'] ;?></td>
                    <td><?php echo $data['jenis'] ;?></td>
                    <td><?php echo $data['stok'] ;?></td>
                    <td>
                    <a class="badge badge-primary" href="index.php?folder=barang-jasa&page=bj-ubah&id_barang_jasa=<?php echo $data['id_barang_jasa'];?>" >Edit</a> |
                    <a class="badge badge-danger" href="barang-jasa/bj-hapus.php?id_barang_jasa=<?php echo $data['id_barang_jasa']; ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>				
                    </td>
                </tr>
                </tr>
        <?php } ?>

</table>

        <!-- <div class="pagination">
            <?php
            $query_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM barang");
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
</html>
