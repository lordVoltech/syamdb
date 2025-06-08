<?php
include 'koneksi.php'; // Pastikan koneksi.php sudah benar

// Mendapatkan Nomor Transaksi dari URL
$no_transaksi = '';
if (isset($_GET['no_transaksi'])) {
    $no_transaksi = mysqli_real_escape_string($conn, $_GET['no_transaksi']);
    // Opsional: Validasi apakah no_transaksi ini benar-benar ada di tabel pembayaran
    // agar tidak menambahkan detail ke transaksi yang tidak valid.
    $check_transaksi = mysqli_query($conn, "SELECT COUNT(*) FROM pembayaran WHERE no_transaksi = '$no_transaksi'");
    $transaksi_exists = mysqli_fetch_row($check_transaksi)[0];
    if ($transaksi_exists == 0) {
        die("Error: Nomor Transaksi '$no_transaksi' tidak ditemukan.");
    }
} else {
    die("Error: Nomor Transaksi tidak ditemukan di URL. Tidak bisa menambahkan detail.");
}

// Mengambil daftar barang/jasa dari database untuk ditampilkan
$query_barang_jasa_list = mysqli_query($conn, "SELECT id_barang_jasa, nama_barang_jasa, harga_satuan FROM barang_jasa ORDER BY nama_barang_jasa ASC");
$barang_jasa_list = [];
while ($row = mysqli_fetch_assoc($query_barang_jasa_list)) {
    $barang_jasa_list[] = $row;
}

// ----- Bagian untuk memproses data ketika form disubmit -----
if (isset($_POST['submit_detail'])) {
    $inserted_count = 0;
    $errors = [];

    // Mengambil data dari form
    $selected_items = isset($_POST['selected_items']) ? $_POST['selected_items'] : [];
    $quantities = isset($_POST['qty']) ? $_POST['qty'] : [];
    $sub_totals = isset($_POST['sub_total_display']) ? $_POST['sub_total_display'] : []; // Ini sub_total yang sudah dihitung JS

    if (empty($selected_items)) {
        $errors[] = "Tidak ada item yang dipilih untuk ditambahkan.";
    } else {
        foreach ($selected_items as $index => $item_id) {
            $item_id = mysqli_real_escape_string($conn, $item_id);
            $qty = isset($quantities[$index]) ? (int)$quantities[$index] : 0;
            $sub_total = isset($sub_totals[$index]) ? (float)str_replace('.', '', str_replace('Rp ', '', $sub_totals[$index])) : 0; // Membersihkan format Rp. dan titik ribuan

            // Validasi dasar
            if ($qty <= 0) {
                $errors[] = "Kuantitas untuk item ID '$item_id' harus lebih dari 0.";
                continue; // Lewati item ini jika kuantitas tidak valid
            }

            // Insert ke tabel detail_pembayaran
            $insert_query = "INSERT INTO detail_pembayaran (no_transaksi, id_barang_jasa, qty, sub_total)
                             VALUES ('$no_transaksi', '$item_id', '$qty', '$sub_total')";

            if (mysqli_query($conn, $insert_query)) {
                $inserted_count++;
            } else {
                $errors[] = "Gagal menambahkan item '$item_id': " . mysqli_error($conn);
            }
        }

        if ($inserted_count > 0) {
            // Setelah insert, perbarui total di tabel pembayaran
            // Ini penting agar Grand Total di transaksi utama terupdate
            $update_total_query = "UPDATE pembayaran SET
                                    total = (SELECT SUM(sub_total) FROM detail_pembayaran WHERE no_transaksi = '$no_transaksi'),
                                    grand_total = (SELECT SUM(sub_total) FROM detail_pembayaran WHERE no_transaksi = '$no_transaksi') - diskon
                                    WHERE no_transaksi = '$no_transaksi'";
            mysqli_query($conn, $update_total_query); // Jalankan update
            // Jika ada error, Anda bisa tangani di sini
            // echo "Total transaksi berhasil diperbarui.<br>";

            echo "<script>alert('Berhasil menambahkan " . $inserted_count . " item. " . (!empty($errors) ? implode(", ", $errors) : "") . "'); window.location.href='index.php?folder=pembayaran&page=p-detail&no_transaksi=$no_transaksi';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan item: " . implode(", ", $errors) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Detail Transaksi</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="text-center mb-4">TAMBAH DETAIL BARANG DAN JASA</h4>
                    <p class="text-center text-muted">Untuk Transaksi: <strong><?php echo htmlspecialchars($no_transaksi); ?></strong></p>

                    <form action="" method="post" id="detailForm">
                        <input type="hidden" name="no_transaksi" value="<?php echo htmlspecialchars($no_transaksi); ?>">

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr class="table-primary">
                                        <th style="width: 5%;">Pilih</th>
                                        <th style="width: 40%;">Nama Barang/Jasa</th>
                                        <th style="width: 15%;">Harga Satuan</th>
                                        <th style="width: 15%;">Qty</th>
                                        <th style="width: 25%;">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($barang_jasa_list)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada Barang/Jasa yang tersedia.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($barang_jasa_list as $index => $item): ?>
                                        <tr class="item-row-data">
                                            <td>
                                                <div class="checkbox-container justify-content-center">
                                                    <input type="checkbox"
                                                           name="selected_items[]"
                                                           value="<?php echo htmlspecialchars($item['id_barang_jasa']); ?>"
                                                           data-harga="<?php echo htmlspecialchars($item['harga_satuan']); ?>"
                                                           onchange="toggleItemRow(this)">
                                                </div>
                                            </td>
                                            <td class="text-left">
                                                <?php echo htmlspecialchars($item['nama_barang_jasa']); ?>
                                            </td>
                                            <td>Rp <?php echo htmlspecialchars(number_format($item['harga_satuan'], 0, ',', '.')); ?></td>
                                            <td>
                                                <input type="number"
                                                       name="qty[]"
                                                       class="form-control form-control-sm qty-input"
                                                       min="1"
                                                       value="1"
                                                       oninput="calculateSubTotal(this)"
                                                       disabled> </td>
                                            <td>
                                                <span class="sub-total-display">Rp 0</span>
                                                <input type="hidden" name="sub_total_display[]" value="0"> </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" name="submit_detail" class="btn btn-primary mr-2">Simpan Detail</button>
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
    // Fungsi untuk mengaktifkan/menonaktifkan input Qty dan mereset Sub Total
    function toggleItemRow(checkbox) {
        const row = checkbox.closest('.item-row-data');
        const qtyInput = row.querySelector('.qty-input');
        const subTotalDisplay = row.querySelector('.sub-total-display');
        const subTotalHiddenInput = row.querySelector('input[name="sub_total_display[]"]');

        if (checkbox.checked) {
            qtyInput.disabled = false;
            qtyInput.value = 1; // Set default quantity to 1 when checked
            calculateSubTotal(qtyInput); // Calculate initial sub-total
        } else {
            qtyInput.disabled = true;
            qtyInput.value = 0; // Reset quantity when unchecked
            subTotalDisplay.textContent = 'Rp 0';
            subTotalHiddenInput.value = '0';
        }
    }

    // Fungsi untuk menghitung Sub Total
    function calculateSubTotal(qtyInput) {
        const row = qtyInput.closest('.item-row-data');
        const checkbox = row.querySelector('input[type="checkbox"]');
        const hargaSatuan = parseFloat(checkbox.dataset.harga);
        const qty = parseInt(qtyInput.value);
        const subTotalDisplay = row.querySelector('.sub-total-display');
        const subTotalHiddenInput = row.querySelector('input[name="sub_total_display[]"]');

        if (checkbox.checked && !isNaN(hargaSatuan) && !isNaN(qty) && qty >= 0) {
            const subTotal = hargaSatuan * qty;
            subTotalDisplay.textContent = 'Rp ' + formatRupiah(subTotal);
            subTotalHiddenInput.value = formatRupiah(subTotal); // Simpan dalam format Rupiah di hidden field
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

    // Inisialisasi: Pastikan semua qty input disabled saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.qty-input').forEach(input => {
            input.disabled = true;
        });
        document.querySelectorAll('.sub-total-display').forEach(span => {
            span.textContent = 'Rp 0';
        });
        document.querySelectorAll('input[name="sub_total_display[]"]').forEach(input => {
            input.value = '0';
        });
    });
</script>

</body>
</html>