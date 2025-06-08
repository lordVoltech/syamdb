<?php
include 'koneksi.php'; // Pastikan koneksi.php sudah benar

$no_transaksi = '';
$id_barang_jasa = '';
$item_data = null;
$harga_satuan = 0; // Untuk perhitungan di JavaScript

// Ambil parameter dari URL
if (isset($_GET['no_transaksi']) && isset($_GET['id_barang_jasa'])) {
    $no_transaksi = mysqli_real_escape_string($conn, $_GET['no_transaksi']);
    $id_barang_jasa = mysqli_real_escape_string($conn, $_GET['id_barang_jasa']);

    // Query untuk mendapatkan detail item yang akan diedit
    $query_item = mysqli_query($conn, "SELECT dp.qty, dp.sub_total, b.nama_barang_jasa, b.harga_satuan
                                    FROM detail_pembayaran dp
                                    JOIN barang_jasa b ON dp.id_barang_jasa = b.id_barang_jasa
                                    WHERE dp.no_transaksi = '$no_transaksi' AND dp.id_barang_jasa = '$id_barang_jasa' LIMIT 1");

    $item_data = mysqli_fetch_assoc($query_item);

    if (!$item_data) {
        die("Error: Item detail tidak ditemukan atau tidak valid.");
    }
    $harga_satuan = $item_data['harga_satuan']; // Ambil harga satuan untuk JS
} else {
    die("Error: Parameter tidak lengkap untuk mengedit detail item.");
}

// Proses ketika form disubmit (UPDATE)
if (isset($_POST['submit_edit_detail'])) {
    $new_qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 0;
    // Mengambil sub_total yang sudah dihitung JavaScript dari hidden field
    $new_sub_total_str = isset($_POST['sub_total_display']) ? $_POST['sub_total_display'] : '0';
    $new_sub_total = (float)str_replace('.', '', str_replace('Rp ', '', $new_sub_total_str));

    if ($new_qty <= 0) {
        echo "<script>alert('Kuantitas harus lebih dari 0.'); window.location.href='index.php?folder=pembayaran&page=p-detail-edit&no_transaksi=$no_transaksi&id_barang_jasa=$id_barang_jasa';</script>";
    } else {
        // Update tabel detail_pembayaran
        $update_detail_query = "UPDATE detail_pembayaran SET
                                qty = '$new_qty',
                                sub_total = '$new_sub_total'
                                WHERE no_transaksi = '$no_transaksi' AND id_barang_jasa = '$id_barang_jasa'";

        if (mysqli_query($conn, $update_detail_query)) {
            // Setelah detail_pembayaran diupdate, perbarui juga total di tabel pembayaran
            $update_total_query = "UPDATE pembayaran SET
                                    total = (SELECT COALESCE(SUM(sub_total), 0) FROM detail_pembayaran WHERE no_transaksi = '$no_transaksi'),
                                    grand_total = (SELECT COALESCE(SUM(sub_total), 0) FROM detail_pembayaran WHERE no_transaksi = '$no_transaksi') - diskon
                                    WHERE no_transaksi = '$no_transaksi'";

            if (mysqli_query($conn, $update_total_query)) {
                echo "<script>alert('Detail item berhasil diperbarui dan total transaksi diperbarui.'); window.location.href='index.php?folder=pembayaran&page=p-detail&no_transaksi=" . urlencode($no_transaksi) . "';</script>";
            } else {
                echo "<script>alert('Detail item berhasil diperbarui, tetapi gagal memperbarui total transaksi: " . mysqli_error($conn) . "'); window.location.href='index.php?folder=pembayaran&page=p-detail&no_transaksi=" . urlencode($no_transaksi) . "';</script>";
            }
        } else {
            echo "<script>alert('Gagal memperbarui detail item: " . mysqli_error($conn) . "'); window.location.href='index.php?folder=pembayaran&page=p-detail-edit&no_transaksi=" . urlencode($no_transaksi) . "&id_barang_jasa=" . urlencode($id_barang_jasa) . "';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Detail Transaksi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="text-center mb-4">EDIT DETAIL BARANG DAN JASA</h4>
                    <p class="text-center text-muted">Untuk Transaksi: <strong><?php echo htmlspecialchars($no_transaksi); ?></strong></p>

                    <form action="" method="post">
                        <input type="hidden" name="no_transaksi" value="<?php echo htmlspecialchars($no_transaksi); ?>">
                        <input type="hidden" name="id_barang_jasa" value="<?php echo htmlspecialchars($id_barang_jasa); ?>">
                        <input type="hidden" id="harga_satuan_hidden" value="<?php echo htmlspecialchars($harga_satuan); ?>">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Nama Barang/Jasa</label>
                            <div class="col-sm-8">
                                <p class="form-control-static"><?php echo htmlspecialchars($item_data['nama_barang_jasa']); ?></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Harga Satuan</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">Rp <?php echo htmlspecialchars(number_format($item_data['harga_satuan'], 0, ',', '.')); ?></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="qty" class="col-sm-4 col-form-label">Kuantitas (Qty)</label>
                            <div class="col-sm-4">
                                <input type="number"
                                       class="form-control"
                                       id="qty"
                                       name="qty"
                                       min="1"
                                       value="<?php echo htmlspecialchars($item_data['qty']); ?>"
                                       oninput="calculateSubTotal()">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Sub Total</label>
                            <div class="col-sm-8">
                                <p class="form-control-static">
                                    <span class="sub-total-display" id="sub_total_display">Rp <?php echo htmlspecialchars(number_format($item_data['sub_total'], 0, ',', '.')); ?></span>
                                    <input type="hidden" name="sub_total_display" id="sub_total_hidden" value="<?php echo htmlspecialchars(number_format($item_data['sub_total'], 0, ',', '.')); ?>">
                                </p>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" name="submit_edit_detail" class="btn btn-primary mr-2">Simpan Perubahan</button>
                            <a class="btn btn-secondary" href="index.php?folder=pembayaran&page=p-detail&no_transaksi=<?php echo htmlspecialchars($no_transaksi); ?>">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Fungsi untuk menghitung Sub Total
    function calculateSubTotal() {
        const qtyInput = document.getElementById('qty');
        const hargaSatuan = parseFloat(document.getElementById('harga_satuan_hidden').value);
        const qty = parseInt(qtyInput.value);
        const subTotalDisplay = document.getElementById('sub_total_display');
        const subTotalHiddenInput = document.getElementById('sub_total_hidden');

        if (!isNaN(hargaSatuan) && !isNaN(qty) && qty >= 0) {
            const subTotal = hargaSatuan * qty;
            subTotalDisplay.textContent = 'Rp ' + formatRupiah(subTotal);
            subTotalHiddenInput.value = formatRupiah(subTotal);
        } else {
            subTotalDisplay.textContent = 'Rp 0';
            subTotalHiddenInput.value = '0';
        }
    }

    // Fungsi untuk memformat angka menjadi Rupiah (tanpa Rp)
    function formatRupiah(angka) {
        var reverse = angka.toString().split('').reverse().join(''),
            ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');
        return ribuan;
    }

    // Panggil fungsi calculateSubTotal saat halaman dimuat
    // untuk memastikan sub total awal terformat dengan benar
    document.addEventListener('DOMContentLoaded', calculateSubTotal);
</script>

</body>
</html>