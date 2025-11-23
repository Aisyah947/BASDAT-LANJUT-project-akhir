<?php
class RestoranModel {
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    // --- MENU ---
    public function getAllMenu(){
        $stmt = $this->conn->prepare("
            SELECT m.*, km.nama_kategori 
            FROM menu m
            JOIN kategori_menu km ON m.id_kategori = km.id_kategori
            ORDER BY m.id_menu ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMenuStatistics(){
        $stats = ['total_menu'=>0, 'makanan'=>0, 'minuman'=>0, 'dessert'=>0, 'snack'=>0];
        $stmtTotal = $this->conn->prepare("SELECT COUNT(*) FROM menu");
        $stmtTotal->execute();
        $stats['total_menu'] = $stmtTotal->fetchColumn();

        $stmtCat = $this->conn->prepare("
            SELECT 
                SUM(CASE WHEN km.nama_kategori='Makanan' THEN 1 ELSE 0 END) AS makanan,
                SUM(CASE WHEN km.nama_kategori='Minuman' THEN 1 ELSE 0 END) AS minuman,
                SUM(CASE WHEN km.nama_kategori='Dessert' THEN 1 ELSE 0 END) AS dessert,
            SUM(CASE WHEN km.nama_kategori='Snack' THEN 1 ELSE 0 END) AS snack
            FROM menu m
            JOIN kategori_menu km ON m.id_kategori = km.id_kategori
        ");
        $stmtCat->execute();
        $res = $stmtCat->fetch(PDO::FETCH_ASSOC);
        $stats['makanan'] = $res['makanan'] ?? 0;
        $stats['minuman'] = $res['minuman'] ?? 0;
        $stats['dessert'] = $res['dessert'] ?? 0;
        $stats['snack'] = $res['snack'] ?? 0;

        return $stats;
    }

    // --- MEJA ---
    public function getAllMeja(){
        $stmt = $this->conn->prepare("SELECT * FROM meja ORDER BY id_meja ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- PESANAN ---
    public function getAllPesanan() {
    $sql = "SELECT p.id_pesanan, 
                   m.nomor_meja, 
                   pel.nama_pelanggan,
                   p.status_orderan
            FROM pesanan p
            JOIN meja m ON m.id_meja = p.id_meja
            JOIN pelanggan pel ON pel.id_pelanggan = p.id_pelanggan
            ORDER BY p.id_pesanan DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function getDetailPesanan($id) {
        $sql = "SELECT 
                    dp.jumlah,
                    menu.nama_menu,
                    menu.harga
                FROM detail_pesanan dp
                JOIN menu ON menu.id_menu = dp.id_menu
                WHERE dp.id_pesanan = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CREATE
        public function tambahPesanan($id_pelanggan, $id_meja, $id_server, $status) {
        $sql = "INSERT INTO pesanan 
                    (id_pelanggan, id_meja, id_server, status_orderan)
                VALUES (:p, :m, :s, :status)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':p' => $id_pelanggan,
            ':m' => $id_meja,
            ':s' => $id_server,
            ':status' => $status
        ]);
    }

    // READ (sudah ada getAllPesanan)
        public function getPesananById($id) {
        $query = "SELECT * FROM pesanan WHERE id_pesanan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // UPDATE
    public function updatePesanan($id, $id_pelanggan, $id_meja, $id_server, $status) {
        $sql = "UPDATE pesanan SET
                    id_pelanggan = :p,
                    id_meja = :m,
                    id_server = :s,
                    status_orderan = :status
                WHERE id_pesanan = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':p' => $id_pelanggan,
            ':m' => $id_meja,
            ':s' => $id_server,
            ':status' => $status
        ]);
    }

    // DELETE
    public function hapusPesanan($id) {

        // HAPUS ITEM PESANAN
        $sql1 = "DELETE FROM detail_pesanan WHERE id_pesanan = :id";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->execute([':id' => $id]);

        // HAPUS PEMBAYARAN
        $sql2 = "DELETE FROM pembayaran WHERE id_pesanan = :id";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->execute([':id' => $id]);

        // HAPUS PESANAN UTAMA
        $sql3 = "DELETE FROM pesanan WHERE id_pesanan = :id";
        $stmt3 = $this->conn->prepare($sql3);
        return $stmt3->execute([':id' => $id]);
    }

    // --- FUNCTION & STORED PROCEDURE ---
    public function hitungTotalPesanan($id_pesanan){
        $stmt = $this->conn->prepare("SELECT hitung_total_pesanan(:id) AS total");
        $stmt->bindParam(':id', $id_pesanan);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function cekStatusPembayaran($id_pesanan){
        $stmt = $this->conn->prepare("SELECT cek_status_pembayaran(:id) AS status");
        $stmt->bindParam(':id', $id_pesanan);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function updateStatusSelesai($id_pesanan){
        $stmt = $this->conn->prepare("CALL update_status_selesai(:id)");
        $stmt->bindParam(':id', $id_pesanan);
        return $stmt->execute();
    }
}
?>
