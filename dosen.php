<?php
require 'connection.php';
$dosen = $pdo->query("SELECT * FROM dosen ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>DOSEN | CORE SYSTEM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <a href="dashboard.php" style="text-decoration: none; color: var(--primary-color);">← Back to Terminal</a>
            <h2>Lecturer Registry</h2>
            <div style="width: 100px;"></div>
        </div>

        <div class="top-bar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Search NIDN or Name...">
            </div>
            <button class="btn btn-primary">Add Faculty Member</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>NIDN</th>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th style="text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($dosen as $d): ?>
                <tr>
                    <td><code style="color: var(--accent-color);"><?= $d['nidn'] ?></code></td>
                    <td><strong><?= htmlspecialchars($d['nama']) ?></strong></td>
                    <td><?= htmlspecialchars($d['email']) ?></td>
                    <td style="text-align: center;">
                        <span style="color: #00ff88; font-size: 12px;">● ACTIVE</span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="particles.js"></script>
</body>
</html>