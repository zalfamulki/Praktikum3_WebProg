<?php
require 'connection.php';

// Handle add grade
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mahasiswa_id = $_POST['mahasiswa_id'];
    $mk_id        = $_POST['mk_id'];
    $skor         = $_POST['skor'];
    if ($mahasiswa_id && $mk_id && $skor !== '') {
        $sql = "INSERT INTO nilai (mahasiswa_id, mk_id, skor) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$mahasiswa_id, $mk_id, $skor])) {
            header("Location: nilai.php");
            exit;
        }
    } else {
        $error = 'Semua field harus diisi!';
    }
}

$query = "SELECT n.*, m.nama as nama_mhs, mk.nama_mk, m.npm, mk.kode_mk
          FROM nilai n
          JOIN mahasiswa m ON n.mahasiswa_id = m.id
          JOIN matakuliah mk ON n.mk_id = mk.id
          ORDER BY n.id DESC";
$dataNilai = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);

$listMhs = $pdo->query("SELECT id, npm, nama FROM mahasiswa ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
$listMK  = $pdo->query("SELECT id, kode_mk, nama_mk FROM matakuliah ORDER BY nama_mk ASC")->fetchAll(PDO::FETCH_ASSOC);

// Grade letter helper
function getGrade($skor) {
    if ($skor >= 85) return ['A', '#00ff88'];
    if ($skor >= 75) return ['B', '#00d2ff'];
    if ($skor >= 65) return ['C', '#f0a500'];
    if ($skor >= 55) return ['D', '#ff9800'];
    return ['E', '#ff4b2b'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NILAI | CORE SYSTEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        select {
            width: 100%;
            padding: 14px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: #fff;
            outline: none;
            transition: 0.3s;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            cursor: pointer;
        }
        select:focus { border-color: var(--primary-color); }
        select option { background: #0d0d1a; color: #e0e0e0; }
        .add-form-panel {
            background: rgba(0,210,255,0.04);
            border: 1px solid rgba(0,210,255,0.2);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .add-form-panel h3 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 16px;
        }
        .form-row { display: flex; gap: 15px; flex-wrap: wrap; }
        .form-row .form-group { flex: 1; min-width: 180px; }
        .form-row .form-group label { font-size: 12px; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <h2>Academic Performance</h2>

        <!-- Add Grade Form -->
        <div class="add-form-panel">
            <h3>📝 Input Nilai Baru</h3>
            <?php if ($error): ?>
                <div style="background: rgba(255,75,43,0.1); border: 1px solid var(--danger-color); padding: 10px 16px; border-radius: 8px; margin-bottom: 15px; color: var(--danger-color); font-size: 13px;">⚠️ <?= $error ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>Mahasiswa</label>
                        <select name="mahasiswa_id" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            <?php foreach ($listMhs as $m): ?>
                            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['npm'] . ' - ' . $m['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mata Kuliah</label>
                        <select name="mk_id" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            <?php foreach ($listMK as $mk): ?>
                            <option value="<?= $mk['id'] ?>"><?= htmlspecialchars($mk['kode_mk'] . ' - ' . $mk['nama_mk']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group" style="max-width: 140px;">
                        <label>Skor (0–100)</label>
                        <input type="text" name="skor" placeholder="0–100" required>
                    </div>
                    <div class="form-group" style="display: flex; align-items: flex-end; max-width: 160px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Simpan</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Grade Table -->
        <div class="top-bar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput" placeholder="Search student or course..." onkeyup="searchTable()">
            </div>
            <div class="stats-card">
                <span style="font-size: 20px;">📊</span>
                <div>
                    <div style="font-size: 10px; opacity: 0.7;">TOTAL RECORDS</div>
                    <div style="font-weight: bold; color: var(--primary-color);"><?= count($dataNilai) ?> Nilai</div>
                </div>
            </div>
        </div>

        <table id="nilaiTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Student Name</th>
                    <th>Course</th>
                    <th style="text-align:center;">Score</th>
                    <th style="text-align:center;">Grade</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach($dataNilai as $n): 
                    [$letter, $color] = getGrade($n['skor']);
                ?>
                <tr>
                    <td style="color: var(--primary-color); font-weight: bold;"><?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?></td>
                    <td><span style="background: rgba(0,210,255,0.1); padding: 3px 8px; border-radius: 5px; font-size: 12px;"><?= htmlspecialchars($n['npm']) ?></span></td>
                    <td><strong><?= htmlspecialchars($n['nama_mhs']) ?></strong></td>
                    <td>
                        <span style="font-size: 11px; color: var(--accent-color);"><?= htmlspecialchars($n['kode_mk']) ?></span><br>
                        <span style="font-size: 13px;"><?= htmlspecialchars($n['nama_mk']) ?></span>
                    </td>
                    <td style="text-align:center; font-size:18px; font-weight:bold; color:<?= $color ?>;"><?= $n['skor'] ?></td>
                    <td style="text-align:center;">
                        <span style="background: <?= $color ?>22; border: 1px solid <?= $color ?>; color: <?= $color ?>; padding: 4px 12px; border-radius: 20px; font-weight: bold; font-size: 14px;"><?= $letter ?></span>
                    </td>
                    <td style="text-align:center;">
                        <?= $n['skor'] >= 55 ? '<span style="color:#00ff88;font-size:12px;">● PASSED</span>' : '<span style="color:#ff4b2b;font-size:12px;">● RETAKE</span>' ?>
                    </td>
                    <td style="text-align:center;">
                        <a href="delete_nilai.php?id=<?= $n['id'] ?>" class="btn btn-delete" style="padding:6px 14px; font-size:12px;" onclick="return confirm('Hapus nilai ini?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="particles.js"></script>
    <script>
    function searchTable() {
        let filter = document.getElementById("searchInput").value.toUpperCase();
        let tr = document.querySelectorAll("#nilaiTable tbody tr");
        tr.forEach(row => {
            let text = row.textContent.toUpperCase();
            row.style.display = text.indexOf(filter) > -1 ? "" : "none";
        });
    }
    </script>
</body>
</html>
