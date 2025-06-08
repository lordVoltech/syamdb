<?php
include 'koneksi.php'; // pastikan file koneksi DB disertakan

if (isset($_GET['no_transaksi'])) {
    $no_transaksi = $_GET['no_transaksi'];
    $query = mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE no_transaksi = '$no_transaksi'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        ?>
        <h2>Detail Transaksi</h2>
        <div style="border:1px solid #ccc; border-radius:10px; padding:20px; width:400px; font-family:sans-serif;">
            <p><strong>No Transaksi:</strong> <?= $data['no_transaksi']; ?></p>
            <p><strong>Tanggal:</strong> <?= $data['tanggal_transaksi']; ?></p>
            <p><strong>KM:</strong> <?= $data['km']; ?></p>
            <p><strong>KM Berikut:</strong> <?= $data['km_berikut']; ?></p>
            <p><strong>Servis Berikut:</strong> <?= $data['servis_berikut']; ?></p>
            <p><strong>Total:</strong> Rp<?= number_format($data['total'], 0, ',', '.'); ?></p>
            <p><strong>Diskon:</strong> Rp<?= number_format($data['diskon'], 0, ',', '.'); ?></p>
            <p><strong>Grand Total:</strong> Rp<?= number_format($data['grand_total'], 0, ',', '.'); ?></p>
            <p><strong>No. Polisi:</strong> <?= $data['no_pol']; ?></p>
            <p><strong>ID Mekanik:</strong> <?= $data['id_mekanik']; ?></p>
            <p><strong>ID Kasir:</strong> <?= $data['id_kasir']; ?></p>
        </div>
        <?php
    } else {
        echo "<p>Data tidak ditemukan, sayang 🥺</p>";
    }
} else {
    echo "<p>No Transaksi tidak ditemukan di URL 😢</p>";
}
?>
