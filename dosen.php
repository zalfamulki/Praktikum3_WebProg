<?php
require 'connection.php';
$dosen = $pdo->query("SELECT * FROM dosen ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOSEN | CORE SYSTEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <h2>Lecturer Registry</h2>

        <div class="top-bar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" placeholder="Search NIDN or Name..." onkeyup="searchTable()">
            </div>
            <div class="stats-card">
                <span style="font-size: 20px;">👨‍🏫</span>
                <div>
                    <div style="font-size: 10px; opacity: 0.7; text-transform: uppercase;">Total Faculty</div>
                    <div style="font-weight: bold; color: var(--secondary-color);"><?= count($dosen) ?> Dosen</div>
                </div>
            </div>
            <a href="tambah_dosen.php" class="btn btn-primary">+ Add Faculty</a>
        </div>

        <table id="dosenTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIDN</th>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: center;">Control</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($dosen as $d): ?>
                <tr>
                    <td style="color: var(--primary-color); font-weight: bold;"><?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?></td>
                    <td><code style="color: var(--accent-color);"><?= htmlspecialchars($d['nidn']) ?></code></td>
                    <td><strong><?= htmlspecialchars($d['nama']) ?></strong></td>
                    <td><?= htmlspecialchars($d['email']) ?></td>
                    <td style="text-align: center;">
                        <span style="color: #00ff88; font-size: 12px;">● ACTIVE</span>
                    </td>
                    <td style="display: flex; gap: 10px; justify-content: center;">
                        <a href="edit_dosen.php?id=<?= $d['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="delete_dosen.php?id=<?= $d['id'] ?>" class="btn btn-delete" onclick="return confirm('Hapus data dosen ini?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="particles.js"></script>
    <script>
    function searchTable() {
        let input = document.getElementById("searchInput");
        let filter = input.value.toUpperCase();
        let table = document.getElementById("dosenTable");
        let tr = table.getElementsByTagName("tr");
        for (let i = 1; i < tr.length; i++) {
            let tdName = tr[i].getElementsByTagName("td")[2];
            let tdNidn = tr[i].getElementsByTagName("td")[1];
            if (tdName || tdNidn) {
                let textValue = (tdName.textContent || "") + (tdNidn.textContent || "");
                tr[i].style.display = textValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    }
    </script>
</body>
</html>
