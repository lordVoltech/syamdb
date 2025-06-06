<?php
// include database connection file
include '../koneksi.php';
 
// Get id from URL to delete that user
if (isset($_GET['no_transaksi'])) {
    $notra=$_GET['no_transaksi'];
}
 
// Delete user row from table based on given id
$result = mysqli_query($conn, "DELETE FROM pembayaran WHERE no_transaksi='$notra'");
 
// After delete redirect to Home, so that latest user list will be displayed.
header("Location:../index.php?folder=pembayaran&page=p-lihat");
exit;
?> 