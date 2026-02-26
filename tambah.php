<?php
require 'connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['npm'];
    $nama = $_POST['nama'];
    $jurusan = $_POST['jurusan'];
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
    <title>ADD ENTRY | CORE SYSTEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width: 500px;">
        <h2>New Entry</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>NIM Identification</label>
                <input type="text" name="npm" placeholder="Input ID Number..." required autofocus>
            </div>
            <div class="form-group">
                <label>Subject Name</label>
                <input type="text" name="nama" placeholder="Input Full Name..." required>
            </div>
            <div class="form-group">
                <label>Department / Major</label>
                <input type="text" name="jurusan" placeholder="Input Department..." required>
            </div>
            
            <div style="margin-top: 40px; display: flex; flex-direction: column; gap: 15px;">
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Initialize Save</button>
                <a href="index.php" class="btn btn-delete" style="justify-content: center;">Abort Mission</a>
            </div>
        </form>
    </div>
    <script src="particles.js"></script>
</body>
</html>
