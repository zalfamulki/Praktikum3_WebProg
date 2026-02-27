<?php
require 'connection.php';
if (!isset($_GET['id'])) { header("Location: dosen.php"); exit; }
$id = (int)$_GET['id'];
$cols = [];
try {
    foreach ($pdo->query("SHOW COLUMNS FROM dosen")->fetchAll(PDO::FETCH_ASSOC) as $c) $cols[] = $c['Field'];
} catch (PDOException $e) { die('Error: ' . htmlspecialchars($e->getMessage())); }
$hasNidn  = in_array('nidn',  $cols);
$hasEmail = in_array('email', $cols);

$stmt = $pdo->prepare("SELECT * FROM dosen WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$data) { header("Location: dosen.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    if (!$nama) { $error = 'Nama wajib diisi.'; }
    else {
        $sets = ['nama = ?']; $vals = [$nama];
        if ($hasNidn)  { $sets[] = 'nidn = ?';  $vals[] = $_POST['nidn']  ?? ''; }
        if ($hasEmail) { $sets[] = 'email = ?'; $vals[] = $_POST['email'] ?? ''; }
        $vals[] = $id;
        try {
            $pdo->prepare("UPDATE dosen SET " . implode(', ', $sets) . " WHERE id = ?")->execute($vals);
            header("Location: dosen.php"); exit;
        } catch (PDOException $e) { $error = htmlspecialchars($e->getMessage()); }
    }
}
?>
<!DOCTYPE html><html lang="id"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDIT FACULTY | CORE SYSTEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head><body>
    <?php include 'sidebar.php'; ?>
    <div class="container" style="max-width:500px;">
        <h2>Modify Faculty</h2>
        <?php if (!empty($error)): ?>
        <div style="background:rgba(255,75,43,0.1);border:1px solid #ff4b2b;padding:12px 20px;border-radius:10px;margin-bottom:20px;color:#ff4b2b;">⚠️ <?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <?php if ($hasNidn): ?>
            <div class="form-group"><label>NIDN</label>
            <input type="text" name="nidn" value="<?= htmlspecialchars($data['nidn'] ?? '') ?>"></div>
            <?php endif; ?>
            <div class="form-group"><label>Full Name <span style="color:#ff4b2b;">*</span></label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama'] ?? '') ?>" required></div>
            <?php if ($hasEmail): ?>
            <div class="form-group"><label>Email Address</label>
            <input type="text" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>"></div>
            <?php endif; ?>
            <div style="margin-top:40px;display:flex;flex-direction:column;gap:15px;">
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Confirm Changes</button>
                <a href="dosen.php" class="btn btn-delete" style="justify-content:center;">Cancel Operation</a>
            </div>
        </form>
    </div>
    <script src="particles.js"></script>
</body></html>
