class GiaoVien extends Model
{
    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT * FROM GIAO_VIEN
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}