<?php
require 'connection.php';
if (!isset($_GET['id'])) { header("Location: matakuliah.php"); exit; }
$id = (int)$_GET['id'];

$cols = [];
try {
    foreach ($pdo->query("SHOW COLUMNS FROM matakuliah")->fetchAll(PDO::FETCH_ASSOC) as $c) $cols[] = $c['Field'];
} catch (PDOException $e) { die('Error: ' . htmlspecialchars($e->getMessage())); }
$hasKodeMK   = in_array('kode_mk',  $cols);
$hasSKS      = in_array('sks',      $cols);
$hasSemester = in_array('semester', $cols);

$stmt = $pdo->prepare("SELECT * FROM matakuliah WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$data) { header("Location: matakuliah.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_mk  = trim($_POST['nama_mk'] ?? '');
    if (!$nama_mk) { $error = 'Nama MK wajib diisi.'; }
    else {
        $sets = ['nama_mk = ?']; $vals = [$nama_mk];
        if ($hasKodeMK)   { $sets[] = 'kode_mk = ?';  $vals[] = $_POST['kode_mk']  ?? ''; }
        if ($hasSKS)      { $sets[] = 'sks = ?';      $vals[] = $_POST['sks']      ?? 0; }
        if ($hasSemester) { $sets[] = 'semester = ?';  $vals[] = $_POST['semester'] ?? 1; }
        $vals[] = $id;
        try {
            $pdo->prepare("UPDATE matakuliah SET " . implode(', ', $sets) . " WHERE id = ?")->execute($vals);
            header("Location: matakuliah.php"); exit;
        } catch (PDOException $e) { $error = htmlspecialchars($e->getMessage()); }
    }
}
?>
<!DOCTYPE html><html lang="id"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDIT COURSE | CORE SYSTEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head><body>
    <?php include 'sidebar.php'; ?>
    <div class="container" style="max-width:500px;">
        <h2>Modify Course</h2>
        <?php if (!empty($error)): ?>
        <div style="background:rgba(255,75,43,0.1);border:1px solid #ff4b2b;padding:12px 20px;border-radius:10px;margin-bottom:20px;color:#ff4b2b;">⚠️ <?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <?php if ($hasKodeMK): ?>
            <div class="form-group"><label>Kode MK</label>
            <input type="text" name="kode_mk" value="<?= htmlspecialchars($data['kode_mk'] ?? '') ?>"></div>
            <?php endif; ?>
            <div class="form-group"><label>Nama Mata Kuliah <span style="color:#ff4b2b;">*</span></label>
            <input type="text" name="nama_mk" value="<?= htmlspecialchars($data['nama_mk'] ?? '') ?>" required></div>
            <?php if ($hasSKS): ?>
            <div class="form-group"><label>SKS</label>
            <input type="number" name="sks" value="<?= htmlspecialchars($data['sks'] ?? '') ?>" min="1" max="6"></div>
            <?php endif; ?>
            <?php if ($hasSemester): ?>
            <div class="form-group"><label>Semester</label>
            <input type="number" name="semester" value="<?= htmlspecialchars($data['semester'] ?? '') ?>" min="1" max="8"></div>
            <?php endif; ?>
            <div style="margin-top:40px;display:flex;flex-direction:column;gap:15px;">
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Confirm Changes</button>
                <a href="matakuliah.php" class="btn btn-delete" style="justify-content:center;">Cancel Operation</a>
            </div>
        </form>
    </div>
    <script src="particles.js"></script>
</body></html>
