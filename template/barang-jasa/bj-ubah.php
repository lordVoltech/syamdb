<?php
    include 'koneksi.php';
    $query = mysqli_query($conn, "Select*from barang_jasa where id_barang_jasa = '$_GET[id_barang_jasa]'");
    $data = mysqli_fetch_array($query);
            
?>
  
  <html>
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
            <div class="card-body">

<form class="form-sample"action="" method="post">

<table class="table table-stripped">
    <h4>Form Ubah</h4>

    <tr>
        <td> id Item </td>
        <td> <input class="form-control" type="number" name="id-item" value="<?php echo $data['id_barang_jasa'];?>" readonly> </td>
    </tr>
    <tr>
        <td> Nama Item </td>
        <td> <input class="form-control" type="text" name="nama" value="<?php echo $data['nama_barang_jasa'];?>" > </td>
    </tr>
    <tr>
        <td> Harga </td>
        <td> <input class="form-control" type="number" name="harga" value="<?php echo $data['harga_satuan'];?>" > </td>
    </tr>

    <tr>
        <td> Jenis </td>
        <td>
            <select class="form-control" name="tipe" id="tipe">
            <option value="barang" <?= ($data['jenis'] == 'barang') ? 'selected' : '' ?>>Barang</option>
            <option value="jasa" <?= ($data['jenis'] == 'jasa') ? 'selected' : '' ?>>Jasa</option>
            </select>
        </td>
    </tr>

    <tr>
        <td> Stok </td>
        <td> <input class="form-control" type="stok" name="stok" value="<?php echo $data['stok'];?>" > </td>
    </tr>

    <tr>
        <td><a class="badge badge-danger" href="barjas.php?page=lihat">Batal</a></td>
        <td><button class="badge badge-success" type="submit" name="proses" value="Simpan"> Ubah </td>
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
    include '../koneksi.php';
  
    $id = $_POST['id-item'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $tipe= $_POST['tipe'];
    $stok= $_POST['stok'];
    
    mysqli_query($conn, "UPDATE barang_jasa SET nama_barang_jasa='$nama', harga_satuan='$harga', jenis='$tipe', stok='$stok' WHERE id_barang_jasa= $id");
    echo"<script>window.location.href = 'index.php?folder=barang-jasa&page=bj-lihat';</script>";
}
?>