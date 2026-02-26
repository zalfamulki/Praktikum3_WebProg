<?php
require 'connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['npm'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    // Gunakan Prepared Statement (?) untuk keamanan
    $sql = "INSERT INTO mahasiswa (npm, nama, jurusan) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$nim, $nama, $jurusan])) {
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa | Sistem Futuristik</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 500px;">
        <h2>Tambah Mahasiswa</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>NIM</label>
                <input type="text" name="npm" placeholder="Masukkan NIM..." required autofocus>
            </div>
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan nama..." required>
            </div>
            <div class="form-group">
                <label>Jurusan</label>
                <input type="text" name="jurusan" placeholder="Masukkan jurusan..." required>
            </div>
            
            <div style="margin-top: 30px; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="flex: 1; border: none;">Simpan Data</button>
                <a href="index.php" class="btn btn-delete" style="text-align: center;">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
