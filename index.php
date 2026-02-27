<?php
require 'connection.php';
$stmt = $pdo->query("SELECT * FROM mahasiswa ORDER BY id DESC");
$mahasiswa = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = count($mahasiswa);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAHASISWA | CORE SYSTEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <h2>Database Mahasiswa</h2>
        
        <div class="top-bar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" placeholder="Cari berdasarkan nama atau NIM..." onkeyup="searchTable()">
            </div>
            
            <div class="stats-card">
                <span style="font-size: 20px;">📊</span>
                <div>
                    <div style="font-size: 10px; opacity: 0.7; text-transform: uppercase;">Total Data</div>
                    <div style="font-weight: bold; color: var(--primary-color);"><?= $total ?> Mahasiswa</div>
                </div>
            </div>
            
            <a href="tambah.php" class="btn btn-primary">Add New Entry</a>
        </div>
        
        <table id="mahasiswaTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Jurusan</th>
                    <th style="text-align: center;">Control</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($mahasiswa as $row): ?>
                <tr>
                    <td style="color: var(--primary-color); font-weight: bold;"><?= str_pad($no++, 2, '0', STR_PAD_LEFT); ?></td>
                    <td><span style="background: rgba(0,210,255,0.1); padding: 4px 8px; border-radius: 5px;"><?= htmlspecialchars($row['npm']); ?></span></td>
                    <td><strong><?= htmlspecialchars($row['nama']); ?></strong></td>
                    <td><?= htmlspecialchars($row['jurusan']); ?></td>
                    <td style="display: flex; gap: 10px; justify-content: center;">
                        <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Inisialisasi penghapusan data?')">Delete</a>
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
        let table = document.getElementById("mahasiswaTable");
        let tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            let tdName = tr[i].getElementsByTagName("td")[2];
            let tdNIM = tr[i].getElementsByTagName("td")[1];
            if (tdName || tdNIM) {
                let textValue = (tdName.textContent || tdName.innerText) + (tdNIM.textContent || tdNIM.innerText);
                if (textValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
    </script>
</body>
</html>
