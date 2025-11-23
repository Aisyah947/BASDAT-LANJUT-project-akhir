<?php
include_once '../models/RestoranModel.php';
include_once '../config/koneksi.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Ambil semua kategori
$kategori = $model->getAllKategori();

// Cek apakah ada filter kategori dari query string
$id_kategori = $_GET['kategori'] ?? null;

if($id_kategori){
    $menuData = $model->getMenuByKategori($id_kategori);
} else {
    $menuData = $model->getAllMenu();
}
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Menu Restoran</title>
<style>
    body { font-family: Arial; margin:20px; background:#f9f9f9; }
    h2 { text-align:center; margin-bottom:20px; }
    .action-buttons { text-align:center; margin-bottom:20px; }
    .btn { padding:10px 15px; text-decoration:none; color:white; border-radius:5px; margin-right:5px; transition:0.3s; display:inline-block; margin-bottom:5px; }
    .btn-laporan { background:#007bff; } .btn-laporan:hover { background:#0056b3; }
    .btn-tambah { background:#28a745; } .btn-tambah:hover { background:#1e7e34; }
    .btn-kategori { background:#6c757d; } .btn-kategori:hover { background:#5a6268; }
    .grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(250px,1fr)); gap:20px; }
    .card { background:white; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1); transition:transform 0.3s; }
    .card:hover { transform:translateY(-5px); }
    .card img { width:100%; height:180px; object-fit:cover; }
    .no-photo { height:180px; line-height:180px; text-align:center; color:#aaa; background:#f0f0f0; }
    .card-content { padding:15px; }
    .card-content h3 { margin:0 0 10px 0; font-size:18px; }
    .card-content p { margin:0 0 8px 0; color:#555; font-size:14px; }
    .status-tersedia { color:green; font-weight:bold; }
    .status-tidak { color:red; font-weight:bold; }
</style>
</head>
<body>

<h2>Daftar Menu Restoran</h2>

<div class="action-buttons">
    <a href="laporan_menu.php" class="btn btn-laporan">Laporan Menu</a>
    <a href="tambah_menu.php" class="btn btn-tambah">Tambah Menu</a>
</div>

<!-- Filter Kategori -->
<div class="action-buttons">
    <a href="menu.php" class="btn btn-kategori">Semua Kategori</a>
    <?php foreach($kategori as $kat): ?>
        <a href="menu.php?kategori=<?= $kat['id_kategori'] ?>" class="btn btn-kategori">
            <?= $kat['nama_kategori'] ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="grid">
    <?php foreach ($menuData as $menu): ?>
    <div class="card">
        <?php if(!empty($menu['foto_menu'])): ?>
            <img src="../uploads/<?= $menu['foto_menu'] ?>" alt="<?= $menu['nama_menu'] ?>">
        <?php else: ?>
            <div class="no-photo">Tidak ada foto</div>
        <?php endif; ?>
        <div class="card-content">
            <h3><?= $menu['nama_menu'] ?></h3>
            <p>Kategori: <?= $menu['nama_kategori'] ?></p>
            <p class="<?= $menu['status_ketersediaan'] ? 'status-tersedia' : 'status-tidak' ?>">
                <?= $menu['status_ketersediaan'] ? 'Tersedia' : 'Tidak Tersedia' ?>
            </p>
            <p>Harga: Rp <?= number_format($menu['harga'],0,',','.') ?></p>
            <p><?= $menu['deskripsi'] ?></p>
        </div>

             <!-- Tombol Edit & Hapus -->
        <div style="margin-top:10px;">
            <a href="edit_menu.php?id=<?= $menu['id_menu'] ?>" style="padding:5px 10px;background:#ffc107;color:white;text-decoration:none;border-radius:3px;">Edit</a>
            <a href="hapus_menu.php?id=<?= $menu['id_menu'] ?>" onclick="return confirm('Yakin ingin menghapus menu ini?');" style="padding:5px 10px;background:#dc3545;color:white;text-decoration:none;border-radius:3px;">Hapus</a>
        </div>
        
    </div>
    <?php endforeach; ?>
</div>

</body>
</html>
<?php include 'footer.php'; ?>