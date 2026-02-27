<?php
require 'connection.php';
$cols = [];
try {
    foreach ($pdo->query("SHOW COLUMNS FROM dosen")->fetchAll(PDO::FETCH_ASSOC) as $c) $cols[] = $c['Field'];
} catch (PDOException $e) { die('Error: ' . htmlspecialchars($e->getMessage())); }
$hasNidn  = in_array('nidn',  $cols);
$hasEmail = in_array('email', $cols);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = trim($_POST['nama']  ?? '');
    $nidn  = trim($_POST['nidn']  ?? '');
    $email = trim($_POST['email'] ?? '');
    if (!$nama) { $error = 'Nama wajib diisi!'; }
    else {
        $fields = ['nama']; $vals = [$nama]; $ph = ['?'];
        if ($hasNidn  && $nidn)  { $fields[] = 'nidn';  $vals[] = $nidn;  $ph[] = '?'; }
        if ($hasEmail && $email) { $fields[] = 'email'; $vals[] = $email; $ph[] = '?'; }
        try {
            $pdo->prepare("INSERT INTO dosen (" . implode(',', $fields) . ") VALUES (" . implode(',', $ph) . ")")->execute($vals);
            header("Location: dosen.php"); exit;
        } catch (PDOException $e) { $error = htmlspecialchars($e->getMessage()); }
    }
}
?>
<!DOCTYPE html><html lang="id"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADD FACULTY | CORE SYSTEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head><body>
    <?php include 'sidebar.php'; ?>
    <div class="container" style="max-width:500px;">
        <h2>New Faculty Member</h2>
        <?php if ($error): ?>
        <div style="background:rgba(255,75,43,0.1);border:1px solid #ff4b2b;padding:12px 20px;border-radius:10px;margin-bottom:20px;color:#ff4b2b;">⚠️ <?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <?php if ($hasNidn): ?>
            <div class="form-group"><label>NIDN</label>
            <input type="text" name="nidn" placeholder="Nomor Induk Dosen Nasional..."></div>
            <?php endif; ?>
            <div class="form-group"><label>Full Name <span style="color:#ff4b2b;">*</span></label>
            <input type="text" name="nama" placeholder="Nama lengkap dosen..." required autofocus></div>
            <?php if ($hasEmail): ?>
            <div class="form-group"><label>Email Address</label>
            <input type="text" name="email" placeholder="Email institusi..."></div>
            <?php endif; ?>
            <div style="margin-top:40px;display:flex;flex-direction:column;gap:15px;">
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Initialize Save</button>
                <a href="dosen.php" class="btn btn-delete" style="justify-content:center;">Abort Mission</a>
            </div>
        </form>
    </div>
    <script src="particles.js"></script>
</body></html>
