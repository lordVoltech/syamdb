<html>

<div class="row">
    <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
            <div class="card-body">

<form action="" method="post">
<table class="table table-stripped">
    <h4 style="text-align:center;">FORM TAMBAH</h4>
    <tr>
        <td> id Pegawai </td>
        <td> <input class="form-control" type="number" name="nama"> </td>
    </tr>
    <tr>
        <td> nama pegawai </td>
        <td> <input class="form-control" type="text" name="harga"> </td>
    </tr>

    <tr>
        <td> jabatan </td>
        <td>
            <select class="form-control" name="tipe" id="tipe">
            <option value="kasir">kasir</option>
            <option value="mekanik">mekanik</option>
            </select>
        </td>
    </tr>

    <tr>
        <td><a class="badge badge-danger" href="pegawai.php?page=lihat">Batal</a></td>
        <td><button class="badge badge-success" type="submit" name="proses" value="Simpan"> Simpan</td>
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

    
    mysqli_query($conn, "INSERT INTO pegawai VALUES('$nama','$harga','$tipe')");
    echo"<script>window.location.href = 'index.php?folder=pegawai&page=bj-lihat';</script>";
}
?>