<?php
// include database connection file
include '../koneksi.php';
 
// Get id from URL to delete that user
if (isset($_GET['id_cos'])) {
    $id_cos=$_GET['id_cos'];
}
 
// Delete user row from table based on given id
$result = mysqli_query($conn, "DELETE FROM costumer WHERE id_cos='$id_cos'");
 
// After delete redirect to Home, so that latest user list will be displayed.
echo"<script>window.location.href = '../index.php?folder=customer&page=c-lihat';</script>";
?> 