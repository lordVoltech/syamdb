<?php
include 'koneksi.php'; // Pastikan koneksi.php sudah benar

// Pastikan parameter no_transaksi dan id_barang_jasa diterima
if (isset($_GET['no_transaksi']) && isset($_GET['id_barang_jasa'])) {
    $no_transaksi = mysqli_real_escape_string($conn, $_GET['no_transaksi']);
    $id_barang_jasa = mysqli_real_escape_string($conn, $_GET['id_barang_jasa']);

    // Query DELETE item dari detail_pembayaran
    $delete_query = "DELETE FROM detail_pembayaran
                     WHERE no_transaksi = '$no_transaksi' AND id_barang_jasa = '$id_barang_jasa'";

    if (mysqli_query($conn, $delete_query)) {
        // Jika penghapusan berhasil, kita perlu memperbarui total dan grand_total di tabel pembayaran.
        // Ini sangat penting agar total transaksi utama tetap akurat.
        $update_total_query = "UPDATE pembayaran SET
                                total = (SELECT COALESCE(SUM(sub_total), 0) FROM detail_pembayaran WHERE no_transaksi = '$no_transaksi'),
                                grand_total = (SELECT COALESCE(SUM(sub_total), 0) FROM detail_pembayaran WHERE no_transaksi = '$no_transaksi') - diskon
                                WHERE no_transaksi = '$no_transaksi'";

        if (mysqli_query($conn, $update_total_query)) {
            // Berhasil dihapus dan total diperbarui
            echo "<script>alert('Item berhasil dihapus dan total transaksi diperbarui.'); window.location.href='index.php?folder=pembayaran&page=p-detail&no_transaksi=$no_transaksi';</script>";
        } else {
            // Berhasil dihapus, tapi gagal perbarui total
            echo "<script>alert('Item berhasil dihapus, tetapi gagal memperbarui total transaksi: " . mysqli_error($conn) . "'); window.location.href='index.php?folder=pembayaran&page=p-detail&no_transaksi=$no_transaksi';</script>";
        }
    } else {
        // Gagal menghapus
        echo "<script>alert('Gagal menghapus item: " . mysqli_error($conn) . "'); window.location.href='index.php?folder=pembayaran&page=p-detail&no_transaksi=$no_transaksi';</script>";
    }
} else {
    // Parameter tidak lengkap
    echo "<script>alert('Parameter tidak lengkap untuk menghapus item.'); window.location.href='index.php?folder=pembayaran&page=p-lihat';</script>";
}

// Tutup koneksi database
mysqli_close($conn);
?>