<?php
    include 'koneksi.php';
    $query = mysqli_query($conn, "Select*from motor where no_pol = '$_GET[no_pol]'");
    $data = mysqli_fetch_array($query);
            
?>
  
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
        <td> <input class="form-control" type="text" name="id" value="<?php echo $data['no_pol'];?>"> </td>
    </tr>
    <tr>
        <td> Motor </td>
        <td> <input class="form-control" type="text" name="nama" value="<?php echo $data['jenis_motor'];?>"> </td>
    </tr>
    <tr>
        <td> CC Mesin </td>
        <td> <input class="form-control" type="text" name="tipe" value="<?php echo $data['jenis_cc'];?>"> </td>
    </tr>

    <tr>
        <td> Pemilik <td>
         <select class="form-control" name="pemilik" style="width:170px;">
        <?php include 'koneksi.php';
        $ambilpelanggan=mysqli_query($conn, "SELECT * FROM costumer");
        while ($pelanggan = mysqli_fetch_array($ambilpelanggan)) {
            $selected = ($data['id_cos'] == $pelanggan['id_cos']) ? 'selected' : '';
            echo "<option value='$pelanggan[id_cos]' $selected>$pelanggan[nama_cos]</option>";
        }
        ?>
        </td>
        </select>
  </td>

    <tr>
        <td><a class="badge badge-danger" href="index.php?folder=motor&page=c-lihat">Batal</a></td>
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
  
    $nama = $_POST['id'];
    $harga = $_POST['nama'];
    $tipe= $_POST['tipe'];
    $pemilik= $_POST['pemilik'];
    
    mysqli_query($conn, "UPDATE motor SET jenis_motor = '$harga', jenis_cc = '$tipe', id_cos = '$pemilik' WHERE no_pol='$nama'");
    echo"<script>window.location.href = 'index.php?folder=motor&page=c-lihat';</script>";
}
?>