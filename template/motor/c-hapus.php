<?php
// include database connection file
include '../koneksi.php';
 
// Get id from URL to delete that user
if (isset($_GET['no_pol'])) {
    $no_pol=$_GET['no_pol'];
}
 
// Delete user row from table based on given id
$result = mysqli_query($conn, "DELETE FROM motor WHERE no_pol='$no_pol'");
 
// After delete redirect to Home, so that latest user list will be displayed.
echo"<script>window.location.href = '../index.php?folder=motor&page=c-lihat';</script>";
?> 