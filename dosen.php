<?php
require 'connection.php';

// ── Deteksi kolom aktual tabel dosen ──────────────────────────────────────────
$cols = [];
try {
    $res = $pdo->query("SHOW COLUMNS FROM dosen");
    foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $c) $cols[] = $c['Field'];
} catch (PDOException $e) {
    die('<div style="font-family:monospace;color:#ff4b2b;padding:40px">
         <b>ERROR — Tabel "dosen" tidak ditemukan.</b><br>
         Pastikan database sudah di-import menggunakan file <code>database_kampus.sql</code>.<br><br>
         Detail: ' . htmlspecialchars($e->getMessage()) . '</div>');
}

// Kolom wajib minimum
$hasPK    = in_array('id',    $cols);
$hasNidn  = in_array('nidn',  $cols);
$hasNama  = in_array('nama',  $cols);
$hasEmail = in_array('email', $cols);

// Kolom opsional
$hasProdi   = in_array('prodi',   $cols);
$hasJabatan = in_array('jabatan', $cols);

if (!$hasNama) {
    die('<div style="font-family:monospace;color:#ff4b2b;padding:40px">
         <b>ERROR — Kolom "nama" tidak ada di tabel dosen.</b><br>
         Periksa struktur tabel Anda.</div>');
}

// ── Ambil data ────────────────────────────────────────────────────────────────
try {
    $dosen = $pdo->query("SELECT * FROM dosen ORDER BY nama ASC")
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
                <span style="font-size:20px;">👨‍🏫</span>
                <div>
                    <div style="font-size:10px;opacity:0.7;text-transform:uppercase;">Total Faculty</div>
                    <div style="font-weight:bold;color:var(--secondary-color);"><?= count($dosen) ?> Dosen</div>
                </div>
            </div>
            <?php if ($hasPK): ?>
            <a href="tambah_dosen.php" class="btn btn-primary">+ Add Faculty</a>
            <?php endif; ?>
        </div>

        <table id="dosenTable">
            <thead>
                <tr>
                    <th>No</th>
                    <?php if ($hasNidn): ?><th>NIDN</th><?php endif; ?>
                    <th>Full Name</th>
                    <?php if ($hasEmail): ?><th>Email Address</th><?php endif; ?>
                    <?php if ($hasJabatan): ?><th>Jabatan</th><?php endif; ?>
                    <?php if ($hasProdi): ?><th>Prodi</th><?php endif; ?>
                    <th style="text-align:center;">Status</th>
                    <?php if ($hasPK): ?><th style="text-align:center;">Control</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($dosen as $d): ?>
                <tr>
                    <td style="color:var(--primary-color);font-weight:bold;"><?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?></td>
                    <?php if ($hasNidn): ?>
                    <td><code style="color:var(--accent-color);"><?= htmlspecialchars($d['nidn'] ?? '') ?></code></td>
                    <?php endif; ?>
                    <td><strong><?= htmlspecialchars($d['nama'] ?? '') ?></strong></td>
                    <?php if ($hasEmail): ?>
                    <td><?= htmlspecialchars($d['email'] ?? '-') ?></td>
                    <?php endif; ?>
                    <?php if ($hasJabatan): ?>
                    <td><?= htmlspecialchars($d['jabatan'] ?? '-') ?></td>
                    <?php endif; ?>
                    <?php if ($hasProdi): ?>
                    <td><?= htmlspecialchars($d['prodi'] ?? '-') ?></td>
                    <?php endif; ?>
                    <td style="text-align:center;">
                        <span style="color:#00ff88;font-size:12px;">● ACTIVE</span>
                    </td>
                    <?php if ($hasPK): ?>
                    <td style="display:flex;gap:10px;justify-content:center;">
                        <a href="edit_dosen.php?id=<?= $d['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="delete_dosen.php?id=<?= $d['id'] ?>"
                           class="btn btn-delete"
                           onclick="return confirm('Hapus data dosen ini?')">Delete</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($dosen)): ?>
                <tr>
                    <td colspan="10" style="text-align:center;opacity:0.5;padding:30px;">
                        Belum ada data dosen. <a href="tambah_dosen.php" style="color:var(--primary-color);">Tambah sekarang →</a>
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
        document.querySelectorAll("#dosenTable tbody tr").forEach(row => {
            row.style.display = row.textContent.toUpperCase().includes(filter) ? "" : "none";
        });
    }
    </script>
</body>
</html>
