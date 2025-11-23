<?php
require_once "../config/koneksi.php";
require_once "../models/RestoranModel.php";

$model = new RestoranModel($conn);

// ambil id dari URL
$id = $_GET['id'] ?? null;

if (!$id) {
    die("<script>alert('ID pesanan tidak ditemukan!'); window.location='laporan_pesanan.php';</script>");
}

$pesanan = $model->getPesananById($id);

// jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomor_meja = $_POST['nomor_meja'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $status = $_POST['status_orderan'];

    if ($model->updatePesanan($id, $nomor_meja, $nama_pelanggan, $status)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='laporan_pesanan.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui pesanan!');</script>";
    }
}
?>

<h2>Edit Pesanan</h2>

<form method="POST">
    <label>Nomor Meja:</label>
    <input type="number" name="nomor_meja" value="<?= htmlspecialchars($pesanan['nomor_meja']) ?>" required>

    <label>Nama Pelanggan:</label>
    <input type="text" name="nama_pelanggan" value="<?= htmlspecialchars($pesanan['nama_pelanggan']) ?>" required>

    <label>Status Orderan:</label>
    <select name="status_orderan" required>
        <option value="Selesai" <?= $pesanan['status_orderan']=='Selesai'?'selected':'' ?>>Selesai</option>
        <option value="Dibatalkan" <?= $pesanan['status_orderan']=='Dibatalkan'?'selected':'' ?>>Dibatalkan</option>
    </select>

    <button class="btn">Update</button>
    <a href="Pesanan.php">Batal</a>
</form>
