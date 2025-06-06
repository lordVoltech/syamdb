<?php
    include 'koneksi.php';
    $query = mysqli_query($conn, "Select*from pegawai where id_pegawai = '$_GET[id_pegawai]'");
    $data = mysqli_fetch_array($query);
            
?>
  
  <html>
  <div class="row">
    <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
            <div class="card-body">
<form action="" method="post">
<table class="table table-stripped">
    <h4>FORM UBAH</h4>

    <tr>
        <td> id Item </td>
        <td> <input class="form-control" type="number" name="id-item" value="<?php echo $data['id_pegawai'];?>" readonly> </td>
    </tr>
    <tr>
        <td> Nama Item </td>
        <td> <input class="form-control" type="text" name="nama" value="<?php echo $data['nama'];?>" > </td>
    </tr>

    <tr>
        <td> Jabatan </td>
        <td>
            <select class="form-control" name="tipe" id="tipe">
            <option value="kasir" <?= ($data['jabatan'] == 'kasir') ? 'selected' : '' ?>>kasir</option>
            <option value="mekanik" <?= ($data['jabatan'] == 'mekanik') ? 'selected' : '' ?>>mekanik</option>
            </select>
        </td>
    </tr>

    <tr>
        <td><a class="badge badge-danger" href="index.php?folder=pegawai&page=bj-lihat">Batal</a></td>
        <td><button class="badge badge-success" type="submit" name="proses" value="Simpan"> Simpan </td>
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
    $tipe= $_POST['tipe'];

    
    mysqli_query($conn, "UPDATE pegawai SET nama='$nama', jabatan='$tipe' WHERE id_pegawai= '$id'");
    echo"<script>window.location.href = 'index.php?folder=pegawai&page=bj-lihat';</script>";
}
?>