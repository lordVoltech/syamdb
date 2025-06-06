<?php
// include database connection file
include '../koneksi.php';
 
// Get id from URL to delete that user
if (isset($_GET['id_barang_jasa'])) {
    $id_barang_jasa=$_GET['id_barang_jasa'];
}
 
// Delete user row from table based on given id
$result = mysqli_query($conn, "DELETE FROM barang_jasa WHERE id_barang_jasa='$id_barang_jasa'");
 
// After delete redirect to Home, so that latest user list will be displayed.
echo"<script>window.location.href = '../index.php?folder=barang-jasa&page=bj-lihat';</script>";
?> 