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

</style>
<h3> AUTO OTOMOTIF </h3> -->
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
    
    mysqli_query($conn, "INSERT INTO costumer VALUES('$nama','$harga','$tipe','')");
    echo"<script>window.location.href = 'index.php?folder=customer&page=c-lihat';</script>";
}
?>