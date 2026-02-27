<?php
require 'connection.php';

// Deteksi kolom
$cols = [];
try {
    foreach ($pdo->query("SHOW COLUMNS FROM matakuliah")->fetchAll(PDO::FETCH_ASSOC) as $c) {
        $cols[] = $c['Field'];
    }
} catch (PDOException $e) {
    die('Tabel matakuliah tidak ditemukan: ' . htmlspecialchars($e->getMessage()));
}
$hasKodeMK   = in_array('kode_mk',  $cols);
$hasSKS      = in_array('sks',      $cols);
$hasSemester = in_array('semester', $cols);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_mk  = trim($_POST['nama_mk'] ?? '');
    $kode_mk  = trim($_POST['kode_mk'] ?? '');
    $sks      = trim($_POST['sks']     ?? '');
    $semester = trim($_POST['semester']?? '');

    if (!$nama_mk) {
        $error = 'Nama Mata Kuliah wajib diisi!';
    } else {
        // Bangun query sesuai kolom yang ada
        $fields = ['nama_mk'];
        $values = [$nama_mk];
        $placeholders = ['?'];
        if ($hasKodeMK && $kode_mk) { $fields[] = 'kode_mk'; $values[] = $kode_mk; $placeholders[] = '?'; }
        if ($hasSKS     && $sks)     { $fields[] = 'sks';     $values[] = $sks;     $placeholders[] = '?'; }
        if ($hasSemester&& $semester){ $fields[] = 'semester'; $values[] = $semester;$placeholders[] = '?'; }

        $sql = "INSERT INTO matakuliah (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        try {
            $pdo->prepare($sql)->execute($values);
            header("Location: matakuliah.php"); exit;
        } catch (PDOException $e) {
            $error = 'Gagal menyimpan: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADD COURSE | CORE SYSTEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container" style="max-width:500px;">
        <h2>New Course</h2>
        <?php if ($error): ?>
        <div style="background:rgba(255,75,43,0.1);border:1px solid #ff4b2b;padding:12px 20px;
                    border-radius:10px;margin-bottom:20px;color:#ff4b2b;">⚠️ <?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <?php if ($hasKodeMK): ?>
            <div class="form-group">
                <label>Kode Mata Kuliah</label>
                <input type="text" name="kode_mk" placeholder="Contoh: IF401" value="<?= htmlspecialchars($_POST['kode_mk'] ?? '') ?>">
            </div>
            <?php endif; ?>
            <div class="form-group">
                <label>Nama Mata Kuliah <span style="color:#ff4b2b;">*</span></label>
                <input type="text" name="nama_mk" placeholder="Nama lengkap mata kuliah..." required value="<?= htmlspecialchars($_POST['nama_mk'] ?? '') ?>">
            </div>
            <?php if ($hasSKS): ?>
            <div class="form-group">
                <label>Jumlah SKS</label>
                <input type="number" name="sks" placeholder="Contoh: 3" min="1" max="6" value="<?= htmlspecialchars($_POST['sks'] ?? '') ?>">
            </div>
            <?php endif; ?>
            <?php if ($hasSemester): ?>
            <div class="form-group">
                <label>Semester</label>
                <input type="number" name="semester" placeholder="Contoh: 4" min="1" max="8" value="<?= htmlspecialchars($_POST['semester'] ?? '') ?>">
            </div>
            <?php endif; ?>
            <div style="margin-top:40px;display:flex;flex-direction:column;gap:15px;">
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Initialize Save</button>
                <a href="matakuliah.php" class="btn btn-delete" style="justify-content:center;">Abort Mission</a>
            </div>
        </form>
    </div>
    <script src="particles.js"></script>
</body>
</html>
