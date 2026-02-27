<?php
require 'connection.php';

// Mengambil total statistik
$totalMhs = $pdo->query("SELECT count(*) FROM mahasiswa")->fetchColumn();
$totalDosen = $pdo->query("SELECT count(*) FROM dosen")->fetchColumn();
$totalMK = $pdo->query("SELECT count(*) FROM matakuliah")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>DASHBOARD | CORE SYSTEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .grid-dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .nav-menu {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>CORE ACADEMIC SYSTEM</h2>
        
        <div class="nav-menu">
            <a href="index.php" class="btn btn-primary">Data Mahasiswa</a>
            <a href="dosen.php" class="btn btn-primary">Data Dosen</a>
            <a href="matakuliah.php" class="btn btn-primary">Mata Kuliah</a>
        </div>

        <div class="grid-dashboard">
            <div class="stats-card" style="padding: 30px; flex-direction: column; align-items: flex-start;">
                <span style="font-size: 40px;">👥</span>
                <div style="font-size: 14px; opacity: 0.7;">TOTAL STUDENTS</div>
                <div style="font-size: 32px; font-weight: bold; color: var(--primary-color);"><?= $totalMhs ?></div>
            </div>

            <div class="stats-card" style="padding: 30px; flex-direction: column; align-items: flex-start; border-color: var(--secondary-color);">
                <span style="font-size: 40px;">👨‍🏫</span>
                <div style="font-size: 14px; opacity: 0.7;">ACTIVE LECTURERS</div>
                <div style="font-size: 32px; font-weight: bold; color: var(--secondary-color);"><?= $totalDosen ?></div>
            </div>

            <div class="stats-card" style="padding: 30px; flex-direction: column; align-items: flex-start; border-color: var(--accent-color);">
                <span style="font-size: 40px;">📚</span>
                <div style="font-size: 14px; opacity: 0.7;">TOTAL COURSES</div>
                <div style="font-size: 32px; font-weight: bold; color: var(--accent-color);"><?= $totalMK ?></div>
            </div>
        </div>

        <div style="margin-top: 50px; text-align: center; opacity: 0.5; font-size: 12px;">
            <p>Sistem Akademik Terintegrasi &bull; Terminal v2.0.26</p>
        </div>
    </div>
    <script src="particles.js"></script>
</body>
</html>