<?php
    include 'koneksi.php';
    $query = mysqli_query($conn, "Select*from costumer where id_cos = '$_GET[id_cos]'");
    $data = mysqli_fetch_array($query);
            
?>
  
  <html>
<!-- <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f1f1f1;
        margin: 0;
        padding: 20px;
    }

    h3 {
        color: #333;
        font-size: 24px;
        text-align: center;
        margin-bottom: 20px;
    }

    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    table {
        width: 100%;
    }

    h4 {
        text-align: center;
        color: #666;
        font-size: 18px;
        margin-bottom: 10px;
    }

    tr {
        line-height: 2;
    }

    td:first-child {
        text-align: right;
        padding-right: 10px;
        color: #666;
        font-weight: bold;
    }

    input[type="text"] {
        width: 100%;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
        font-size: 14px;
    }

    input[type="submit"] {
        padding: 8px 15px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 14px;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    .kembali {
        
        padding: 10px 10px;
        background-color: #333;
        color: #fff;
        text-decoration: none;
        border-radius: 3px;
        font-size: 14px;
    }

    .kembali:hover {
        background-color: #555;
    }
</style> -->
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                <table class="table table-stripped">
                    <h4>FORM UBAH ORANG</h4>

                    <tr>
                        <td> id Customer </td>
                        <td> <input class="form-control" type="number" name="id-item" value="<?php echo $data['id_cos'];?>" readonly> </td>
                    </tr>
                    <tr>
                        <td> Nama Customer </td>
                        <td> <input class="form-control" type="text" name="nama" value="<?php echo $data['nama_cos'];?>" > </td>
                    </tr>


                    <tr>
                        <td> Jenis </td>
                        <td>
                            <select class="form-control" name="tipe" id="tipe">
                            <option value="Member" <?= ($data['jenis_member'] == 'Member') ? 'selected' : '' ?>>Member</option>
                            <option value="Belum Member" <?= ($data['jenis_member'] == 'Belum Member') ? 'selected' : '' ?>>Belum Member</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><a class="badge badge-danger" href="c-lihat.php">Batal</a></td>
                        <td><button class="badge badge-success" type="submit" name="proses" value="Simpan">Simpan </td>
                    </tr>
                </form>
                </table>
        </div>
        </div>
    </div>
</div>
</html>

<?php

if (isset($_POST['proses'])){
    include 'koneksi.php';
  
    $id = $_POST['id-item'];
    $nama = $_POST['nama'];
    $tipe = $_POST['tipe'];

    mysqli_query($conn, "UPDATE costumer SET nama_cos='$nama', jenis_member='$tipe' WHERE id_cos= $id");
    echo"<script>window.location.href = 'index.php?folder=customer&page=c-lihat';</script>";
}
?>