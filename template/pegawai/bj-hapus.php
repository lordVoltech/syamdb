<?php
// include database connection file
include '../koneksi.php';
 
// Get id from URL to delete that user
if (isset($_GET['id_pegawai'])) {
    $id_pegawai=$_GET['id_pegawai'];
}
 
// Delete user row from table based on given id
$result = mysqli_query($conn, "DELETE FROM pegawai WHERE id_pegawai='$id_pegawai'");
 
// After delete redirect to Home, so that latest user list will be displayed.
echo"<script>window.location.href = '../index.php?folder=pegawai&page=bj-lihat';</script>";
?> 