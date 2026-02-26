<?php
require 'connection.php';
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $npm = $_POST['npm'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
    $sql = "UPDATE mahasiswa SET npm = ?, nama = ?, jurusan = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$npm, $nama, $jurusan, $id])) {
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
    <title>MODIFY DATA | CORE SYSTEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 500px;">
        <h2>Modify Entry</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>NIM Identification</label>
                <input type="text" name="npm" value="<?= htmlspecialchars($data['npm']); ?>" required>
            </div>
            <div class="form-group">
                <label>Subject Name</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required>
            </div>
            <div class="form-group">
                <label>Department / Major</label>
                <input type="text" name="jurusan" value="<?= htmlspecialchars($data['jurusan']); ?>" required>
            </div>
            
            <div style="margin-top: 40px; display: flex; flex-direction: column; gap: 15px;">
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Confirm Changes</button>
                <a href="index.php" class="btn btn-delete" style="justify-content: center;">Cancel Operation</a>
            </div>
        </form>
    </div>
    <script src="particles.js"></script>
</body>
</html>
