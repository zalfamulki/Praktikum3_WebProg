<?php
require 'connection.php';

// ── Deteksi kolom aktual tabel matakuliah ─────────────────────────────────────
$cols = [];
try {
    $res = $pdo->query("SHOW COLUMNS FROM matakuliah");
    foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $c) $cols[] = $c['Field'];
} catch (PDOException $e) {
    die('<div style="font-family:monospace;color:#ff4b2b;padding:40px">
         <b>ERROR — Tabel "matakuliah" tidak ditemukan.</b><br>
         Pastikan database sudah di-import menggunakan file <code>database_kampus.sql</code>.<br><br>
         Detail: ' . htmlspecialchars($e->getMessage()) . '</div>');
}

$hasPK      = in_array('id',       $cols);
$hasNamaMK  = in_array('nama_mk',  $cols);
$hasKodeMK  = in_array('kode_mk',  $cols);
$hasSKS     = in_array('sks',      $cols);
$hasSemester= in_array('semester', $cols);

if (!$hasNamaMK) {
    die('<div style="font-family:monospace;color:#ff4b2b;padding:40px">
         <b>ERROR — Kolom "nama_mk" tidak ditemukan di tabel matakuliah.</b><br>
         Struktur kolom yang ada: <code>' . implode(', ', $cols) . '</code></div>');
}

// ── Ambil data ────────────────────────────────────────────────────────────────
try {
    $matakuliah = $pdo->query("SELECT * FROM matakuliah ORDER BY nama_mk ASC")
                      ->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('<div style="font-family:monospace;color:#ff4b2b;padding:40px">
         Query gagal: ' . htmlspecialchars($e->getMessage()) . '</div>');
}
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
                <input type="text" id="searchInput" placeholder="Search course name..." onkeyup="searchTable()">
            </div>
            <div class="stats-card">
                <span style="font-size:20px;">📚</span>
                <div>
                    <div style="font-size:10px;opacity:0.7;text-transform:uppercase;">Total Courses</div>
                    <div style="font-weight:bold;color:var(--accent-color);"><?= count($matakuliah) ?> Mata Kuliah</div>
                </div>
            </div>
            <?php if ($hasPK): ?>
            <a href="tambah_mk.php" class="btn btn-primary">+ Add Course</a>
            <?php endif; ?>
        </div>

        <?php if (!$hasKodeMK || !$hasSKS || !$hasSemester): ?>
        <div style="background:rgba(240,165,0,0.08);border:1px solid rgba(240,165,0,0.3);
                    border-radius:10px;padding:12px 20px;margin-bottom:20px;
                    font-size:12px;color:rgba(240,165,0,0.9);">
            ⚠️ Tabel <code>matakuliah</code> di database Anda hanya memiliki kolom:
            <strong><?= implode(', ', $cols) ?></strong>.
            Jalankan <code>database_kampus.sql</code> untuk struktur lengkap
            (kode_mk, sks, semester).
        </div>
        <?php endif; ?>

        <table id="mkTable">
            <thead>
                <tr>
                    <th>No</th>
                    <?php if ($hasKodeMK): ?><th>Kode MK</th><?php endif; ?>
                    <th>Nama Mata Kuliah</th>
                    <?php if ($hasSKS): ?><th style="text-align:center;">SKS</th><?php endif; ?>
                    <?php if ($hasSemester): ?><th style="text-align:center;">Semester</th><?php endif; ?>
                    <?php if ($hasPK): ?><th style="text-align:center;">Control</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($matakuliah as $mk): ?>
                <tr>
                    <td style="color:var(--primary-color);font-weight:bold;"><?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?></td>

                    <?php if ($hasKodeMK): ?>
                    <td>
                        <span style="background:rgba(58,237,255,0.1);padding:4px 8px;
                                     border-radius:5px;color:var(--accent-color);">
                            <?= htmlspecialchars($mk['kode_mk'] ?? '') ?>
                        </span>
                    </td>
                    <?php endif; ?>

                    <td><strong><?= htmlspecialchars($mk['nama_mk'] ?? '') ?></strong></td>

                    <?php if ($hasSKS): ?>
                    <td style="text-align:center;">
                        <span style="background:rgba(157,80,187,0.2);padding:4px 10px;
                                     border-radius:20px;color:var(--secondary-color);">
                            <?= htmlspecialchars($mk['sks'] ?? '-') ?> SKS
                        </span>
                    </td>
                    <?php endif; ?>

                    <?php if ($hasSemester): ?>
                    <td style="text-align:center;">
                        Semester <?= htmlspecialchars($mk['semester'] ?? '-') ?>
                    </td>
                    <?php endif; ?>

                    <?php if ($hasPK): ?>
                    <td style="display:flex;gap:10px;justify-content:center;">
                        <a href="edit_mk.php?id=<?= $mk['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="delete_mk.php?id=<?= $mk['id'] ?>"
                           class="btn btn-delete"
                           onclick="return confirm('Hapus mata kuliah ini?')">Delete</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($matakuliah)): ?>
                <tr>
                    <td colspan="10" style="text-align:center;opacity:0.5;padding:30px;">
                        Belum ada data mata kuliah. <a href="tambah_mk.php" style="color:var(--primary-color);">Tambah sekarang →</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="particles.js"></script>
    <script>
    function searchTable() {
        const filter = document.getElementById("searchInput").value.toUpperCase();
        document.querySelectorAll("#mkTable tbody tr").forEach(row => {
            row.style.display = row.textContent.toUpperCase().includes(filter) ? "" : "none";
        });
    }
    </script>
</body>
</html>
