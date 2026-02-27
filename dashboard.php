<?php
require 'connection.php';
$totalMhs = $pdo->query("SELECT count(*) FROM mahasiswa")->fetchColumn();
$totalDosen = $pdo->query("SELECT count(*) FROM dosen")->fetchColumn();
$totalMK = $pdo->query("SELECT count(*) FROM matakuliah")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>DASHBOARD | CORE SYSTEM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <h2>SYSTEM ANALYTICS</h2>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            <div class="stats-card">
                <div>
                    <div style="font-size: 10px; opacity: 0.7;">TOTAL STUDENTS</div>
                    <div style="font-size: 24px; color: var(--primary-color); font-weight: bold;"><?= $totalMhs ?></div>
                </div>
            </div>
            <div class="stats-card" style="border-color: var(--secondary-color);">
                <div>
                    <div style="font-size: 10px; opacity: 0.7;">ACTIVE FACULTY</div>
                    <div style="font-size: 24px; color: var(--secondary-color); font-weight: bold;"><?= $totalDosen ?></div>
                </div>
            </div>
            <div class="stats-card" style="border-color: var(--accent-color);">
                <div>
                    <div style="font-size: 10px; opacity: 0.7;">COURSES READY</div>
                    <div style="font-size: 24px; color: var(--accent-color); font-weight: bold;"><?= $totalMK ?></div>
                </div>
            </div>
        </div>

        <div style="margin-top: 40px; padding: 20px; background: rgba(255,255,255,0.02); border-radius: 15px; border: 1px solid var(--glass-border);">
            <h3 style="color: var(--primary-color);">System Logs</h3>
            <p style="font-family: monospace; font-size: 13px; opacity: 0.6;">> Database connection established... [OK]<br>> Core system modules loaded... [OK]<br>> Particle engine running... [OK]</p>
        </div>
    </div>
    <script src="particles.js"></script>
</body>
</html>