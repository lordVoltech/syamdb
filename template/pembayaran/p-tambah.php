<?php
ob_start();
?>
<html>
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 style="text-align:center">FORM TAMBAH</h4>
                <form action="" method="post">
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor Transaksi</label>
                                <input class="form-control" type="number" name="no-nota">
                            </div>

                            <div class="form-group">
                                <label>Tanggal Hari Ini</label>
                                <input class="form-control" type="date" name="tanggal">
                            </div>

                            <div class="form-group">
                                <label>Tanggal Servis Berikutnya</label>
                                <input class="form-control" type="date" name="servisberikut">
                            </div>

                            <div class="form-group">
                                <label>KM Saat Ini</label>
                                <input class="form-control" type="number" name="km">
                            </div>

                            <div class="form-group">
                                <label>KM Maintenance</label>
                                <input class="form-control" type="number" name="kmberikut">
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total</label>
                                <input class="form-control" type="number" name="total">
                            </div>

                            <div class="form-group">
                                <label>Diskon</label>
                                <input class="form-control" type="number" name="diskon">
                            </div>

                            <div class="form-group">
                                <label>Grand Total</label>
                                <input class="form-control" type="number" name="grandtotal">
                            </div>

                            <div class="form-group">
                                <label>Nopol</label>
                                <select class="form-control" name="nopol">
                                    <option value="">--Pilih--</option>
                                    <?php include 'koneksi.php'; $query=mysqli_query($conn, "SELECT * FROM motor"); while ($data = mysqli_fetch_array($query)) { ?>
                                        <option value="<?php echo $data['no_pol'];?>"><?php echo $data['no_pol'];?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Mekanik</label>
                                <select class="form-control" name="mekanik">
                                    <option value="">--Pilih--</option>
                                    <?php $query=mysqli_query($conn, "SELECT * FROM pegawai where jabatan = 'mekanik'"); while ($data = mysqli_fetch_array($query)) { ?>
                                        <option value="<?php echo $data['id_pegawai'];?>"><?php echo $data['nama'];?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Kasir</label>
                                <select class="form-control" name="kasir">
                                    <option value="">--Pilih--</option>
                                    <?php $query=mysqli_query($conn, "SELECT * FROM pegawai where jabatan = 'kasir'"); while ($data = mysqli_fetch_array($query)) { ?>
                                        <option value="<?php echo $data['id_pegawai'];?>"><?php echo $data['nama'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a class="badge badge-danger" href="index.php?folder=pembayaran&page=p-lihat">Batal</a>
                        <button class="badge badge-success" type="submit" name="proses" value="Simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</html>

<?php
if (isset($_POST['proses'])){
    include 'koneksi.php';
  
    $noNota = $_POST['no-nota'];
    $tanggal = $_POST['tanggal'];
    $servisBerikut = $_POST['servisberikut'];
    $km = $_POST['km'];
    $kmBerikut = $_POST['kmberikut'];
    $total = $_POST['total'];
    $diskon = $_POST['diskon'];
    $grandTotal = $_POST['grandtotal'];
    $nopol = $_POST['nopol'];
    $mekanik = $_POST['mekanik'];
    $kasir = $_POST['kasir'];
    
    mysqli_query($conn, "INSERT INTO pembayaran VALUES('$noNota', '$tanggal', '$km', '$kmBerikut', '$servisBerikut', '$total', '$diskon', '$grandTotal', '$nopol', '$mekanik', '$kasir')");
    echo"<script>window.location.href = 'index.php?folder=pembayaran&page=p-lihat';</script>";
    ob_end_flush();
}
?>