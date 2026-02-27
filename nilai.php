<?php
require 'connection.php';

// ── Deteksi kolom dari semua tabel yang terlibat ──────────────────────────────
function getColumns(PDO $pdo, string $table): array {
    try {
        $rows = $pdo->query("SHOW COLUMNS FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
        return array_column($rows, 'Field');
    } catch (PDOException $e) {
        return [];
    }
}

$colsNilai = getColumns($pdo, 'nilai');
$colsMhs   = getColumns($pdo, 'mahasiswa');
$colsMK    = getColumns($pdo, 'matakuliah');

// Cek tabel nilai minimal ada
if (empty($colsNilai)) {
    die('<div style="font-family:monospace;color:#ff4b2b;padding:40px">
         <b>ERROR — Tabel "nilai" tidak ditemukan.</b><br>
         Import <code>database_kampus.sql</code> terlebih dahulu.</div>');
}

// Kolom opsional yang mungkin tidak ada
$hasNPM     = in_array('npm',     $colsMhs);
$hasKodeMK  = in_array('kode_mk', $colsMK);
$hasSkor    = in_array('skor',    $colsNilai);   // kolom nilai
$hasNilai   = in_array('nilai',   $colsNilai);   // nama alternatif

// Tentukan nama kolom nilai yang benar
$nilaiCol = $hasSkor ? 'skor' : ($hasNilai ? 'nilai' : null);

if (!$nilaiCol) {
    die('<div style="font-family:monospace;color:#ff4b2b;padding:40px">
         <b>ERROR — Kolom nilai tidak ditemukan.</b><br>
         Kolom yang ada di tabel nilai: <code>' . implode(', ', $colsNilai) . '</code><br>
         Tambahkan kolom <code>skor</code> atau <code>nilai</code> ke tabel nilai.</div>');
}

// ── Handle POST: Simpan nilai baru ────────────────────────────────────────────
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mahasiswa_id = $_POST['mahasiswa_id'] ?? '';
    $mk_id        = $_POST['mk_id']        ?? '';
    $skorInput    = $_POST['skor']         ?? '';

    if ($mahasiswa_id && $mk_id && $skorInput !== '') {
        try {
            $sql  = "INSERT INTO nilai (mahasiswa_id, mk_id, `$nilaiCol`) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$mahasiswa_id, $mk_id, $skorInput])) {
                header("Location: nilai.php");
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Gagal menyimpan: ' . htmlspecialchars($e->getMessage());
        }
    } else {
        $error = 'Semua field harus diisi!';
    }
}

// ── Bangun query JOIN secara dinamis ──────────────────────────────────────────
$selectParts = ["n.*", "m.nama AS nama_mhs", "mk.nama_mk"];
if ($hasNPM)    $selectParts[] = "m.npm";
if ($hasKodeMK) $selectParts[] = "mk.kode_mk";

$selectStr = implode(', ', $selectParts);

try {
    $dataNilai = $pdo->query(
        "SELECT $selectStr
         FROM nilai n
         JOIN mahasiswa  m  ON n.mahasiswa_id = m.id
         JOIN matakuliah mk ON n.mk_id = mk.id
         ORDER BY n.id DESC"
    )->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('<div style="font-family:monospace;color:#ff4b2b;padding:40px">
         <b>Query JOIN gagal.</b><br>' . htmlspecialchars($e->getMessage()) . '<br><br>
         Pastikan kolom <code>mahasiswa_id</code> dan <code>mk_id</code> ada di tabel nilai,
         dan tabel <code>mahasiswa</code> serta <code>matakuliah</code> sudah ada.</div>');
}

// ── Dropdown data ─────────────────────────────────────────────────────────────
try {
    $selectMhs = $hasNPM
        ? "SELECT id, npm, nama FROM mahasiswa ORDER BY nama ASC"
        : "SELECT id, nama FROM mahasiswa ORDER BY nama ASC";
    $listMhs = $pdo->query($selectMhs)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $listMhs = []; }

try {
    $selectMK = $hasKodeMK
        ? "SELECT id, kode_mk, nama_mk FROM matakuliah ORDER BY nama_mk ASC"
        : "SELECT id, nama_mk FROM matakuliah ORDER BY nama_mk ASC";
    $listMK = $pdo->query($selectMK)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { $listMK = []; }

// ── Helper: konversi skor ke grade ───────────────────────────────────────────
function getGrade(float $skor): array {
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
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            color: #fff;
            outline: none;
            transition: 0.3s;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%2300d2ff' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
        }
        select:focus { border-color: var(--primary-color); box-shadow: 0 0 10px rgba(0,210,255,0.2); }
        select option { background: #0d0d1a; color: #e0e0e0; }

        .add-panel {
            background: rgba(0,210,255,0.04);
            border: 1px solid rgba(0,210,255,0.18);
            border-radius: 16px;
            padding: 22px 24px;
            margin-bottom: 28px;
        }
        .add-panel h3 {
            color: var(--primary-color);
            font-size: 14px;
            font-weight: 600;
            margin: 0 0 18px;
            letter-spacing: 0.5px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr 130px 140px;
            gap: 14px;
            align-items: end;
        }
        @media (max-width: 768px) {
            .form-row { grid-template-columns: 1fr; }
        }
        .form-row .form-group { margin: 0; }
        .form-row label { font-size: 11px; margin-bottom: 8px; }
        .error-box {
            background: rgba(255,75,43,0.08);
            border: 1px solid rgba(255,75,43,0.4);
            border-radius: 8px;
            padding: 10px 16px;
            margin-bottom: 14px;
            color: #ff4b2b;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <h2>Academic Performance</h2>

        <!-- Form Input Nilai -->
        <div class="add-panel">
            <h3>📝 Input Nilai Baru</h3>
            <?php if ($error): ?>
            <div class="error-box">⚠️ <?= $error ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group">
                        <label>Mahasiswa</label>
                        <select name="mahasiswa_id" required>
                            <option value="">— Pilih Mahasiswa —</option>
                            <?php foreach ($listMhs as $m): ?>
                            <option value="<?= $m['id'] ?>">
                                <?= htmlspecialchars(($m['npm'] ?? '') . ($m['npm'] ? ' — ' : '') . $m['nama']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Mata Kuliah</label>
                        <select name="mk_id" required>
                            <option value="">— Pilih Mata Kuliah —</option>
                            <?php foreach ($listMK as $mk): ?>
                            <option value="<?= $mk['id'] ?>">
                                <?= htmlspecialchars(($mk['kode_mk'] ?? '') . ($mk['kode_mk'] ? ' — ' : '') . $mk['nama_mk']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Skor (0–100)</label>
                        <input type="number" name="skor" min="0" max="100"
                               placeholder="0 – 100" required
                               style="width:100%;padding:14px;background:rgba(255,255,255,0.05);
                                      border:1px solid var(--glass-border);border-radius:12px;
                                      color:#fff;outline:none;box-sizing:border-box;
                                      font-family:'Poppins',sans-serif;">
                    </div>

                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary"
                                style="width:100%;justify-content:center;">
                            Simpan Nilai
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Header -->
        <div class="top-bar">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchInput"
                       placeholder="Search student or course..."
                       onkeyup="searchTable()">
            </div>
            <div class="stats-card">
                <span style="font-size:20px;">📊</span>
                <div>
                    <div style="font-size:10px;opacity:0.7;">TOTAL RECORDS</div>
                    <div style="font-weight:bold;color:var(--primary-color);"><?= count($dataNilai) ?> Nilai</div>
                </div>
            </div>
        </div>

        <!-- Nilai Table -->
        <table id="nilaiTable">
            <thead>
                <tr>
                    <th>No</th>
                    <?php if ($hasNPM): ?><th>NIM</th><?php endif; ?>
                    <th>Nama Mahasiswa</th>
                    <?php if ($hasKodeMK): ?><th>Kode MK</th><?php endif; ?>
                    <th>Mata Kuliah</th>
                    <th style="text-align:center;">Skor</th>
                    <th style="text-align:center;">Grade</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($dataNilai as $n):
                    $skorVal = (float)($n[$nilaiCol] ?? 0);
                    [$letter, $color] = getGrade($skorVal);
                ?>
                <tr>
                    <td style="color:var(--primary-color);font-weight:bold;">
                        <?= str_pad($no++, 2, '0', STR_PAD_LEFT) ?>
                    </td>

                    <?php if ($hasNPM): ?>
                    <td>
                        <span style="background:rgba(0,210,255,0.1);padding:3px 8px;
                                     border-radius:5px;font-size:12px;">
                            <?= htmlspecialchars($n['npm'] ?? '') ?>
                        </span>
                    </td>
                    <?php endif; ?>

                    <td><strong><?= htmlspecialchars($n['nama_mhs'] ?? '') ?></strong></td>

                    <?php if ($hasKodeMK): ?>
                    <td>
                        <span style="color:var(--accent-color);font-size:12px;">
                            <?= htmlspecialchars($n['kode_mk'] ?? '') ?>
                        </span>
                    </td>
                    <?php endif; ?>

                    <td><?= htmlspecialchars($n['nama_mk'] ?? '') ?></td>

                    <td style="text-align:center;font-size:18px;font-weight:bold;color:<?= $color ?>;">
                        <?= $skorVal ?>
                    </td>

                    <td style="text-align:center;">
                        <span style="background:<?= $color ?>22;border:1px solid <?= $color ?>;
                                     color:<?= $color ?>;padding:4px 12px;
                                     border-radius:20px;font-weight:bold;font-size:14px;">
                            <?= $letter ?>
                        </span>
                    </td>

                    <td style="text-align:center;">
                        <?php if ($skorVal >= 55): ?>
                        <span style="color:#00ff88;font-size:12px;">● PASSED</span>
                        <?php else: ?>
                        <span style="color:#ff4b2b;font-size:12px;">● RETAKE</span>
                        <?php endif; ?>
                    </td>

                    <td style="text-align:center;">
                        <a href="delete_nilai.php?id=<?= $n['id'] ?>"
                           class="btn btn-delete"
                           style="padding:6px 14px;font-size:12px;"
                           onclick="return confirm('Hapus nilai ini?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($dataNilai)): ?>
                <tr>
                    <td colspan="10" style="text-align:center;opacity:0.5;padding:30px;">
                        Belum ada data nilai. Gunakan form di atas untuk menambahkan.
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
        document.querySelectorAll("#nilaiTable tbody tr").forEach(row => {
            row.style.display = row.textContent.toUpperCase().includes(filter) ? "" : "none";
        });
    }
    </script>
</body>
</html>
