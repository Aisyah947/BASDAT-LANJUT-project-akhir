<?php
include 'BASDAT/config/koneksi.php';
include 'BASDAT/models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    if($model->hapusPesanan($id)) {
        header("Location: Pesanan.php?deleted=1");
    } else {
        header("Location: Pesanan.php?error=1");
    }
    exit;
}
?>