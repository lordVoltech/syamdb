<html>
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
            <div class="card-body">
<h3 style="text-align:center;"> Form Tambah </h3>

<form action="" method="post">
<table class="table table">
    <tr>
        <td> Nama Item </td>
        <td> <input class="form-control"type="text" name="nama"> </td>
    </tr>
    <tr>
        <td> Harga </td>
        <td> <input class="form-control" type="number" name="harga"> </td>
    </tr>

    <tr>
        <td> Jenis </td>
        <td>
            <select class="form-control" name="tipe" id="tipe">
            <option value="barang">Barang</option>
            <option value="jasa">Jasa</option>
            </select>
        </td>
    </tr>

    <tr>
        <td> Stok </td>
        <td> <input class="form-control" type="stok" name="stok"> </td>
    </tr>

    <tr>
        <td><a class="badge badge-danger" href="index.php?folder=barang-jasa&page=bj-lihat">Batal</a></td>
        <td><button class="badge badge-success" type="submit" name="proses" value="Simpan"> Simpan </td>
    </tr>

</table>

</form>

        </div>
        </div>
    </div>
</div>
</html>

<?php

if (isset($_POST['proses'])){
    include 'koneksi.php';
  
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $tipe= $_POST['tipe'];
    $stok= $_POST['stok'];
    
    mysqli_query($conn, "INSERT INTO barang_jasa VALUES('','$nama','$harga','$tipe','$stok')");
    echo"<script>window.location.href = 'index.php?folder=barang-jasa&page=bj-lihat';</script>";
}
?>