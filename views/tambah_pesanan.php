<?php
include("../config/koneksi.php");
include("../models/RestoranModel.php");

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_meja = $_POST['id_meja'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $status_orderan = $_POST['status_orderan'];
    
    if($model->tambahPesanan($id_meja, $nama_pelanggan, $status_orderan)) {
        header("Location: Pesanan.php?success=1");
        exit;
    }
}
?>

<?php include 'header.php'; ?>
<link rel="stylesheet" href="../css/style.css">
<br>
<h2>Tambah Pesanan Baru</h2>
<br>
<form method="POST">
    <label>Nomor Meja:</label>
    <input type="number" name="nomor_meja" required>
    
    <label>Nama Pelanggan:</label>
    <input type="text" name="nama_pelanggan" required>
    
    <label>Status Orderan:</label>
    <select name="status_orderan" required>
        <option value="Selesai">Selesai</option>
        <option value="Dibatalkan">Dibatalkan</option>
    </select>
    
    <button class="btn">Simpan</button>
    <a href="Pesanan.php">Batal</a>
</form>

<?php include 'footer.php'; ?>