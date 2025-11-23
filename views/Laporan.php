<?php include 'header.php'; ?>
<br>
<h2>Dashboard Laporan Penjualan</h2>
<br>
<div class="dashboard-cards">
    <div class="card">
        <h3>Total Penjualan Hari Ini</h3>
        <div class="number">Rp 5.500.000</div>
    </div>
    <div class="card">
        <h3>Menu Terlaris</h3>
        <div class="number">Nasi Goreng</div>
    </div>
    <div class="card">
        <h3>Shift Terakhir</h3>
        <div class="number">Shift Malam</div>
    </div>
</div>
<br>
<h3>Daftar Penjualan</h3>
<table>
    <thead>
        <tr>
            <th>ID Penjualan</th>
            <th>Menu</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Shift</th>
        </tr>
    </thead>
    <link rel="stylesheet" href="/BASDAT/css/style.css"> 
    <tbody>
        <tr>
            <td>1</td>
            <td>Nasi Goreng</td>
            <td>10</td>
            <td>Rp 250.000</td>
            <td>Pagi</td>
        </tr>
    </tbody>
</table>

<?php include 'footer.php'; ?>
