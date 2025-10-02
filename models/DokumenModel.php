<?php
class DokumenModel
{
    private $conn;
    private $table_dokumen = "dokumen";
    private $table_kategori = "kategori";
    private $table_dokumen_pemda = "dokumen_pemda";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method untuk mendapatkan dokumen berdasarkan kategori dan nama_jenis dengan status publikasi
    public function getDokumenByKategoriAndJenis($id_kategori, $nama_jenis = null)
    {
        $query = "SELECT
                    d.id_dokumen,
                    d.judul,
                    d.kandungan_informasi,
                    d.terbitkan_sebagai,
                    d.tipe_file,
                    d.upload_file,
                    d.created_at,
                    d.updated_at,
                    k.nama_kategori,
                    dp.nama_jenis
                  FROM " . $this->table_dokumen . " d
                  INNER JOIN " . $this->table_kategori . " k ON d.id_kategori = k.id_kategori
                  LEFT JOIN " . $this->table_dokumen_pemda . " dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                  WHERE d.id_kategori = :id_kategori
                  AND d.status = 'publikasi'";

        if ($nama_jenis !== null) {
            $query .= " AND dp.nama_jenis = :nama_jenis";
        }

        $query .= " ORDER BY d.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_kategori', $id_kategori);

        if ($nama_jenis !== null) {
            $stmt->bindParam(':nama_jenis', $nama_jenis);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk search dokumen berdasarkan judul dengan status publikasi
    public function searchDokumen($keyword, $id_kategori = null)
    {
        $query = "SELECT
                    d.id_dokumen,
                    d.judul,
                    d.kandungan_informasi,
                    d.terbitkan_sebagai,
                    d.tipe_file,
                    d.upload_file,
                    d.created_at,
                    d.updated_at,
                    k.nama_kategori,
                    dp.nama_jenis
                  FROM " . $this->table_dokumen . " d
                  INNER JOIN " . $this->table_kategori . " k ON d.id_kategori = k.id_kategori
                  LEFT JOIN " . $this->table_dokumen_pemda . " dp ON d.id_dokumen_pemda = dp.id_dokumen_pemda
                  WHERE d.status = 'publikasi'
                  AND (d.judul LIKE :keyword
                       OR d.kandungan_informasi LIKE :keyword
                       OR d.terbitkan_sebagai LIKE :keyword)";

        if ($id_kategori !== null) {
            $query .= " AND d.id_kategori = :id_kategori";
        }

        $query .= " ORDER BY d.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $keyword_param = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $keyword_param);

        if ($id_kategori !== null) {
            $stmt->bindParam(':id_kategori', $id_kategori);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method untuk mendapatkan nama kategori berdasarkan ID
    public function getKategoriById($id_kategori)
    {
        $query = "SELECT nama_kategori FROM " . $this->table_kategori . " WHERE id_kategori = :id_kategori LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_kategori', $id_kategori);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['nama_kategori'] : '';
    }

    // Method untuk mendapatkan semua kategori
    public function getAllKategori()
    {
        $query = "SELECT id_kategori, nama_kategori FROM " . $this->table_kategori . " ORDER BY nama_kategori ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
