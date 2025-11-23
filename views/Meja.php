<?php
include '../config/koneksi.php';
include '../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$allMeja = $model->getAllMeja();
?>

<?php include 'header.php'; ?>
<br>
<h2>Laporan Meja</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nomor</th>
            <th>Kapasitas</th>
            <th>Status</th>
        </tr>
    </thead>
    <link rel="stylesheet" href="/BASDAT/css/style.css"> 
    <tbody>
        <?php foreach($allMeja as $meja): ?>
        <tr>
            <td><?= $meja['id_meja'] ?></td>
            <td><?= $meja['nomor_meja'] ?></td>
            <td><?= $meja['kapasitas'] ?></td>
            <td><?= $meja['status_meja'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
