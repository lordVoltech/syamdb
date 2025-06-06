<html>
<div class="row">
    <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
            <div class="card-body">
<form action="" method="post">
<table class="table table-stripped">
    <h4>FORM TAMBAH</h4>
    <tr>
        <td> No Polisi </td>
        <td> <input class="form-control" type="text" name="id"> </td>
    </tr>
    <tr>
        <td> Motor </td>
        <td> <input class="form-control" type="text" name="nama"> </td>
    </tr>
    <tr>
        <td> CC Mesin </td>
        <td> <input class="form-control" type="text" name="tipe"> </td>
    </tr>

    <tr>
        <td> Pemilik <td>
        <select class="form-control" name="pemilik" style="width:170px;">
        <option value="">--Pilih--</option>
        <?php include 'koneksi.php'; $query=mysqli_query($conn, "SELECT * FROM costumer"); while ($data = mysqli_fetch_array($query)) {
        ?>
            <option value="<?php echo $data['id_cos'];?>" >
            <?php echo $data['nama_cos'];?></option>
        <?php
        }
        ?></td>
        </select>
  </td>
  </tr>

    <tr>
        <td><a class="badge badge-danger" href="index.php?folder=motor&page=c-lihat">Batal</a></td>
        <td><button class="badge badge-success" type="submit" name="proses" value="Simpan"> Sipman </td>
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
  
    $nama = $_POST['id'];
    $harga = $_POST['nama'];
    $tipe= $_POST['tipe'];
    $pemilik= $_POST['pemilik'];
    
    mysqli_query($conn, "INSERT INTO motor VALUES('$nama','$harga','$tipe','$pemilik')");
    echo"<script>window.location.href = 'index.php?folder=motor&page=c-lihat';</script>";
}
?>