<?php
    include 'koneksi.php';
    $query = mysqli_query($conn, "Select*from pembayaran where no_transaksi = '$_GET[no_transaksi]'");
    $data = mysqli_fetch_array($query);
            
?>
  
<html>
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="text-center">FORM TAMBAH</h4>
                <form action="" method="post">
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor Transaksi</label>
                                <input class="form-control" type="text" name="no-nota" value="<?php echo $data['no_transaksi']; ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label>Tanggal Hari Ini</label>
                                <input class="form-control" type="date" name="tanggal" value="<?php echo $data['tanggal_transaksi']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Tanggal Servis Berikutnya</label>
                                <input class="form-control" type="date" name="servisberikut" value="<?php echo $data['servis_berikut']; ?>">
                            </div>

                            <div class="form-group">
                                <label>KM Saat Ini</label>
                                <input class="form-control" type="number" name="km" value="<?php echo $data['km']; ?>">
                            </div>

                            <div class="form-group">
                                <label>KM Maintenance</label>
                                <input class="form-control" type="number" name="kmberikut" value="<?php echo $data['km_berikut']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Total</label>
                                <input class="form-control" type="number" name="total" value="<?php echo $data['total']; ?>">
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Diskon</label>
                                <input class="form-control" type="number" name="diskon" value="<?php echo $data['diskon']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Grand Total</label>
                                <input class="form-control" type="number" name="grandtotal" value="<?php echo $data['grand_total']; ?>">
                            </div>

                            <div class="form-group">
                                <label>Nopol</label>
                                <select class="form-control" name="nopol">
                                    <?php
                                    include 'koneksi.php';
                                    $ambilpelanggan = mysqli_query($conn, "SELECT * FROM motor");
                                    while ($pelanggan = mysqli_fetch_array($ambilpelanggan)) {
                                        $selected = ($data['no_pol'] == $pelanggan['no_pol']) ? 'selected' : '';
                                        echo "<option value='$pelanggan[no_pol]' $selected>$pelanggan[no_pol]</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Mekanik</label>
                                <select class="form-control" name="mekanik">
                                    <?php
                                    $ambilpegawai = mysqli_query($conn, "SELECT * FROM pegawai WHERE jabatan = 'mekanik'");
                                    while ($pegawai = mysqli_fetch_array($ambilpegawai)) {
                                        $selected = ($data['id_pegawai'] == $pegawai['id_pegawai']) ? 'selected' : '';
                                        echo "<option value='$pegawai[id_pegawai]' $selected>$pegawai[nama]</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Kasir</label>
                                <select class="form-control" name="kasir">
                                    <?php
                                    $ambilpegawai = mysqli_query($conn, "SELECT * FROM pegawai WHERE jabatan = 'kasir'");
                                    while ($pegawai = mysqli_fetch_array($ambilpegawai)) {
                                        $selected = ($data['id_pegawai'] == $pegawai['id_pegawai']) ? 'selected' : '';
                                        echo "<option value='$pegawai[id_pegawai]' $selected>$pegawai[nama]</option>";
                                    }
                                    ?>
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
    
    mysqli_query($conn, "UPDATE pembayaran SET tanggal_transaksi='$tanggal', servis_berikut='$servisBerikut', km='$km', km_berikut='$kmBerikut', total='$total', diskon='$diskon', grand_total='$grandTotal', id_mekanik='$mekanik', id_kasir='$kasir' WHERE no_transaksi='$noNota'");
    echo"<script>window.location.href = 'index.php?folder=pembayaran&page=p-lihat';</script>";
}
?>