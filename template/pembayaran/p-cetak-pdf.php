<?php
// --- DEBUGGING: AKTIFKAN LAPORAN ERROR ---
error_reporting(E_ALL); // Melaporkan semua jenis error
ini_set('display_errors', 1); // Menampilkan error di output HTML
ini_set('display_startup_errors', 1); // Menampilkan error saat startup
// ----------------------------------------


require_once __DIR__ . '/../../vendor/autoload.php'; // Naik dua level direktori
include 'koneksi.php'; // Pastikan koneksi.php sudah benar

use Dompdf\Dompdf;
use Dompdf\Options;

// Pastikan no_transaksi diterima dari GET
if (!isset($_GET['no_transaksi'])) {
    die("Error: Nomor Transaksi tidak ditemukan.");
}

$no_transaksi = mysqli_real_escape_string($conn, $_GET['no_transaksi']);

// Ambil data pembayaran
$query_pembayaran = mysqli_query($conn, "SELECT * FROM pembayaran WHERE no_transaksi = '$no_transaksi'");
$data_pembayaran = mysqli_fetch_array($query_pembayaran);

if (!$data_pembayaran) {
    die("Error: Data transaksi tidak ditemukan untuk Nomor Transaksi: " . htmlspecialchars($no_transaksi));
}

// Ambil detail barang dan jasa
$query_barang_jasa = mysqli_query($conn, "SELECT dp.id_barang_jasa, b.nama_barang_jasa, b.harga_satuan, dp.qty, dp.sub_total
                                            FROM detail_pembayaran dp
                                            JOIN barang_jasa b ON dp.id_barang_jasa = b.id_barang_jasa
                                            WHERE dp.no_transaksi = '$no_transaksi'");
$barang_jasa_details = [];
while ($row = mysqli_fetch_assoc($query_barang_jasa)) {
    $barang_jasa_details[] = $row;
}

// Ambil nama mekanik
$mechanic_name = 'N/A';
if (isset($data_pembayaran['id_mekanik'])) {
    $query_mekanik = mysqli_query($conn, "SELECT nama FROM pegawai WHERE id_pegawai = '" . mysqli_real_escape_string($conn, $data_pembayaran['id_mekanik']) . "' LIMIT 1");
    if ($mekanik_row = mysqli_fetch_array($query_mekanik)) {
        $mechanic_name = $mekanik_row['nama'];
    }
}

// Ambil nama kasir
$kasir_name = 'N/A';
if (isset($data_pembayaran['id_kasir'])) {
    $query_kasir = mysqli_query($conn, "SELECT nama FROM pegawai WHERE id_pegawai = '" . mysqli_real_escape_string($conn, $data_pembayaran['id_kasir']) . "' LIMIT 1");
    if ($kasir_row = mysqli_fetch_array($query_kasir)) {
        $kasir_name = $kasir_row['nama'];
    }
}

// =================================================================================================
// HTML Content for PDF (Ini yang akan diubah menjadi PDF)
// =================================================================================================
$html = '
<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran - ' . htmlspecialchars($data_pembayaran['no_transaksi']) . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 2px 0; font-size: 10px; }
        .invoice-info { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .invoice-info td { padding: 3px; vertical-align: top; }
        .invoice-info .label { font-weight: bold; width: 30%; }
        .invoice-info .value { width: 70%; }
        .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .detail-table th, .detail-table td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        .detail-table th { background-color: #f2f2f2; }
        .detail-table .text-right { text-align: right; }
        .summary-info { width: 100%; margin-top: 10px; }
        .summary-info td { padding: 3px 0; }
        .summary-info .label { font-weight: bold; width: 70%; text-align: right; }
        .summary-info .value { width: 30%; text-align: right; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Nama Bengkel Anda</h1>
        <p>Alamat Bengkel Anda, Kota, Kode Pos</p>
        <p>Telepon: (XXX) XXX-XXXX | Email: info@bengkelanda.com</p>
        <hr style="border: 0.5px solid #ccc; margin-top: 10px;">
    </div>

    <table class="invoice-info">
        <tr>
            <td class="label">Nomor Transaksi:</td>
            <td class="value">' . htmlspecialchars($data_pembayaran['no_transaksi']) . '</td>
            <td class="label">Nopol:</td>
            <td class="value">' . htmlspecialchars($data_pembayaran['no_pol']) . '</td>
        </tr>
        <tr>
            <td class="label">Tanggal Transaksi:</td>
            <td class="value">' . htmlspecialchars(date('d-m-Y', strtotime($data_pembayaran['tanggal_transaksi']))) . '</td>
            <td class="label">KM Saat Ini:</td>
            <td class="value">' . htmlspecialchars(number_format($data_pembayaran['km'], 0, ',', '.')) . '</td>
        </tr>
        <tr>
            <td class="label">Servis Berikutnya:</td>
            <td class="value">' . htmlspecialchars(date('d-m-Y', strtotime($data_pembayaran['servis_berikut']))) . '</td>
            <td class="label">KM Maintenance:</td>
            <td class="value">' . htmlspecialchars(number_format($data_pembayaran['km_berikut'], 0, ',', '.')) . '</td>
        </tr>
        <tr>
            <td class="label">Mekanik:</td>
            <td class="value">' . htmlspecialchars($mechanic_name) . '</td>
            <td class="label">Kasir:</td>
            <td class="value">' . htmlspecialchars($kasir_name) . '</td>
        </tr>
    </table>

    <h3>Detail Barang dan Jasa:</h3>
    <table class="detail-table">
        <thead>
            <tr>
                <th style="width: 45%;">Nama Barang/Jasa</th>
                <th style="width: 15%;">Harga Satuan</th>
                <th style="width: 10%;">Qty</th>
                <th style="width: 30%;" class="text-right">Sub Total</th>
            </tr>
        </thead>
        <tbody>';

        if (empty($barang_jasa_details)) {
            $html .= '<tr><td colspan="4" style="text-align: center;">Tidak ada barang/jasa yang tercatat.</td></tr>';
        } else {
            foreach ($barang_jasa_details as $item) {
                $html .= '
                <tr>
                    <td>' . htmlspecialchars($item['nama_barang_jasa']) . '</td>
                    <td>Rp ' . htmlspecialchars(number_format($item['harga_satuan'], 0, ',', '.')) . '</td>
                    <td>' . htmlspecialchars($item['qty']) . '</td>
                    <td class="text-right">Rp ' . htmlspecialchars(number_format($item['sub_total'], 0, ',', '.')) . '</td>
                </tr>';
            }
        }

$html .= '
        </tbody>
    </table>

    <table class="summary-info">
        <tr>
            <td class="label">Total:</td>
            <td class="value">Rp ' . htmlspecialchars(number_format($data_pembayaran['total'], 0, ',', '.')) . '</td>
        </tr>
        <tr>
            <td class="label">Diskon:</td>
            <td class="value">Rp ' . htmlspecialchars(number_format($data_pembayaran['diskon'], 0, ',', '.')) . '</td>
        </tr>
        <tr>
            <td class="label">Grand Total:</td>
            <td class="value">Rp ' . htmlspecialchars(number_format($data_pembayaran['grand_total'], 0, ',', '.')) . '</td>
        </tr>
    </table>

    <div class="footer">
        <p>Terima kasih telah melakukan servis di bengkel kami.</p>
        <p>Hormat Kami,</p>
        <p>(Karyawan Bengkel)</p>
    </div>
</body>
</html>';

// =================================================================================================
// Dompdf Processing
// =================================================================================================

// Konfigurasi Dompdf (opsional, tapi direkomendasikan)
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // Penting jika ada gambar eksternal atau font dari URL
$dompdf = new Dompdf($options);

// Load HTML ke Dompdf
$dompdf->loadHtml($html);

// (Opsional) Atur ukuran dan orientasi kertas
$dompdf->setPaper('A4', 'portrait'); // Bisa juga 'letter', 'legal', 'landscape'

// Render PDF (jalankan konversi HTML ke PDF)
$dompdf->render();

// Output PDF ke browser (force download atau tampilkan di browser)
$dompdf->stream("Struk_Pembayaran_" . $no_transaksi . ".pdf", array("Attachment" => false)); // "Attachment" => false untuk menampilkan di browser, true untuk langsung download

// Tutup koneksi database
mysqli_close($conn);
?>