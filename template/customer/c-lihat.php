<html>
<!-- <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        
    }
    
    h3 {
        color: #333;
        text-align: center;
        margin-top: 20px;
    }
    
    form {
        margin: 20px auto;
        width: 80%;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    table td {
        padding: 10px;
        border: 1px solid #ccc;
    }
    
    table th {
        padding: 10px;
        background-color: #333;
        color: #fff;
        border: 1px solid #ccc;
    }
    
    table tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    
    input[type="text"] {
        padding: 5px;
        width: 100%;
        box-sizing: border-box;
    }
    
    input[type="submit"] {
        padding: 8px 20px;
        background-color: #333;
        color: #fff;
        border: none;
        cursor: pointer;
    }
    
    input[type="submit"]:hover {
        background-color: #555;
    }
    
    .edit,
    .hapus,
    .kembali {
        padding: 7.5px 10px;
        background-color: #333;
        color: #fff;
        text-decoration: none;
        border-radius: 3px;
    }
    
    .edit:hover,
    .hapus:hover,
    .kembali:hover {
        background-color: #555;
    }
    
    .edit {
        margin-right: 5px;
    }
    
    .kembali {
        margin-top: 1px;
    }
    
    .kembali:hover {
        background-color: #f2f2f2;
        color: #333;
    }

    .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 5px 10px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            font-size: 14px;
            margin-right: 5px;
        }

        .pagination a.current {
            background-color: #555;
        }
</style> -->
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
            <div class="card-body">
                <h3 style="text-align : center;"> Data Anggota</h3>
                <form>
                    <a class="badge badge-success" href="index.php?folder=customer&page=c-tambah">Tambah</a>
                    <!-- <a class="kembali" href="../menu.php">Kembali</a> -->
                    <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr >
                            <th>No ID</th>
                            <th>Nama</th>
                            <th>Member</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tr>
                            <?php
                                include 'koneksi.php';
                                $limit = 10; // Jumlah baris per halaman
                                $hu = isset($_GET['hu']) ? $_GET['hu'] : 1;
                                $start = ($hu - 1) * $limit;
                            
                                $query = mysqli_query($conn, "SELECT * FROM costumer LIMIT $start, $limit");
                                while ($data=mysqli_fetch_array($query)){
                                    ?>
                                    <tr>
                                        <td><?php echo $data['id_cos'] ;?></td>
                                        <td><?php echo $data['nama_cos'] ;?></td>
                                        <td><?php echo $data['jenis_member'] ;?></td>
                                        <td>
                                        <a class="badge badge-primary" href="index.php?folder=customer&page=c-ubah&id_cos=<?php echo $data['id_cos'];?>" >Edit</a> 
                                        <a class="badge badge-danger" href="customer/c-hapus.php?id_cos=<?php echo $data['id_cos']; ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>				
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</html>
