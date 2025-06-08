<html>

<div class="row">
    <div class="col-lg-8 grid-margin stretch-card mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="" method="post">
                <table class="table table-strippped">
                    <h4>FORM TAMBAH</h4>
                    <tr>
                        <td> id costumer </td>
                        <td> <input class="form-control" type="number" name="id"> </td>
                    </tr>
                    <tr>
                        <td> Nama Customer </td>
                        <td> <input  class="form-control" type="text" name="nama"> </td>
                    </tr>

                    <tr>
                        <td> Member </td>
                        <td>
                            <select class="form-control" name="tipe" id="tipe">
                            <option value="Member">Member</option>
                            <option value="Belum Member">Belum Member</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><a class="badge badge-danger" href="c-lihat.php">Batal</a></td>
                        <td><button class="badge badge-success" type="submit" name="proses" value="Simpan">Simpan </td>
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
    
    mysqli_query($conn, "INSERT INTO costumer VALUES('$nama','$harga','$tipe')");
    echo"<script>window.location.href = 'index.php?folder=customer&page=c-lihat';</script>";
}
?>