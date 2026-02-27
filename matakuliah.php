<?php
require 'connection.php';
$matakuliah = $pdo->query("SELECT * FROM matakuliah ORDER BY nama_mk ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MATA KULIAH | CORE SYSTEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <h2>Course Catalog</h2>

        <div class="top-bar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" placeholder="Search course code or name..." onkeyup="searchTable()">
            </div>
            <div class="stats-card">
                <span style="font-size: 20px;">📚</span>
                <div>
                    <div style="font-size: 10px; opacity: 0.7; text-transform: uppercase;">Total Courses</div>
                    <div style="font-weight: bold; color: var(--accent-color);"><?= count($matakuliah) ?> Mata Kuliah</div>
                </div>
            </div>
            <a href="tambah_mk.php" class="btn btn-primary">+ Add Course</a>
        </div>

        <table id="mkTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode MK</th>
                    <th>Nama Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Semester</th>
                    <th style="text-align: center;">Control</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($matakuliah as $mk): ?>
                <tr>
                    <td style="color: var(--primary-color); font-weight: bold;"><?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?></td>
                    <td><span style="background: rgba(58,237,255,0.1); padding: 4px 8px; border-radius: 5px; color: var(--accent-color);"><?= htmlspecialchars($mk['kode_mk']) ?></span></td>
                    <td><strong><?= htmlspecialchars($mk['nama_mk']) ?></strong></td>
                    <td style="text-align: center;">
                        <span style="background: rgba(157,80,187,0.2); padding: 4px 10px; border-radius: 20px; color: var(--secondary-color);"><?= $mk['sks'] ?> SKS</span>
                    </td>
                    <td style="text-align: center;">Semester <?= htmlspecialchars($mk['semester']) ?></td>
                    <td style="display: flex; gap: 10px; justify-content: center;">
                        <a href="edit_mk.php?id=<?= $mk['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="delete_mk.php?id=<?= $mk['id'] ?>" class="btn btn-delete" onclick="return confirm('Hapus mata kuliah ini?')">Delete</a>
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
        let table = document.getElementById("mkTable");
        let tr = table.getElementsByTagName("tr");
        for (let i = 1; i < tr.length; i++) {
            let tds = tr[i].getElementsByTagName("td");
            let textValue = "";
            for (let j = 0; j < tds.length; j++) textValue += tds[j].textContent;
            tr[i].style.display = textValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
        }
    }
    </script>
</body>
</html>
