<?php

declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

$pdo = getPdo();
initializeDatabase($pdo);

$search = trim((string) ($_GET['search'] ?? ''));
$position = trim((string) ($_GET['position'] ?? ''));
$minExperience = trim((string) ($_GET['min_experience'] ?? ''));

$whereClauses = [];
$params = [];

if ($search !== '') {
    $whereClauses[] = '(full_name LIKE :search OR email LIKE :search OR phone LIKE :search OR address LIKE :search OR position_applied LIKE :search)';
    $params[':search'] = '%' . $search . '%';
}

if ($position !== '') {
    $whereClauses[] = 'position_applied = :position';
    $params[':position'] = $position;
}

if ($minExperience !== '') {
    if (ctype_digit($minExperience)) {
        $whereClauses[] = 'years_experience >= :min_experience';
        $params[':min_experience'] = (int) $minExperience;
    } else {
        $minExperience = '';
    }
}

$sql = 'SELECT * FROM applicants';
if ($whereClauses) {
    $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
}
$sql .= ' ORDER BY id DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$applicants = $stmt->fetchAll();

$positionsStmt = $pdo->query('SELECT DISTINCT position_applied FROM applicants ORDER BY position_applied ASC');
$positions = $positionsStmt->fetchAll(PDO::FETCH_COLUMN);

$flash = getFlash();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP CRUD using PDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">PHP CRUD Activity using PDO</h1>
            <p class="text-muted mb-0">Applicants Information with image upload</p>
        </div>
        <a href="create.php" class="btn btn-primary">Add New Applicant</a>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
            <?= e($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-lg-5">
                    <label class="form-label">Search</label>
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Name, email, phone, address, or position"
                        value="<?= e($search) ?>"
                    >
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Position</label>
                    <select name="position" class="form-select">
                        <option value="">All positions</option>
                        <?php foreach ($positions as $positionOption): ?>
                            <option value="<?= e((string) $positionOption) ?>" <?= $position === (string) $positionOption ? 'selected' : '' ?>>
                                <?= e((string) $positionOption) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2">
                    <label class="form-label">Min Experience</label>
                    <input type="number" min="0" name="min_experience" class="form-control" value="<?= e($minExperience) ?>">
                </div>
                <div class="col-lg-2 d-grid">
                    <button type="submit" class="btn btn-dark">Apply Filter</button>
                </div>
                <div class="col-12 d-flex justify-content-between align-items-center pt-1">
                    <small class="text-muted">Showing <?= count($applicants) ?> record(s)</small>
                    <a href="index.php" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Position</th>
                        <th>Experience</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!$applicants): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No records yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($applicants as $applicant): ?>
                            <tr>
                                <td><?= (int) $applicant['id'] ?></td>
                                <td>
                                    <img src="<?= e($applicant['image_path']) ?>" alt="Applicant Image" class="rounded" style="width:56px;height:56px;object-fit:cover;">
                                </td>
                                <td><?= e($applicant['full_name']) ?></td>
                                <td><?= e($applicant['email']) ?></td>
                                <td><?= e($applicant['phone']) ?></td>
                                <td><?= e($applicant['position_applied']) ?></td>
                                <td><?= (int) $applicant['years_experience'] ?> year(s)</td>
                                <td class="text-end">
                                    <a href="edit.php?id=<?= (int) $applicant['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form action="delete.php" method="post" class="d-inline" onsubmit="return confirm('Delete this record?');">
                                        <input type="hidden" name="id" value="<?= (int) $applicant['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
