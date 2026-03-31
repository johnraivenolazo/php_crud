<?php

declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

$pdo = getPdo();
initializeDatabase($pdo);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    setFlash('danger', 'Invalid record ID.');
    redirect('index.php');
}

$stmt = $pdo->prepare('SELECT * FROM applicants WHERE id = :id');
$stmt->execute([':id' => $id]);
$applicant = $stmt->fetch();

if (!$applicant) {
    setFlash('danger', 'Record not found.');
    redirect('index.php');
}

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

$view = [
    'full_name' => (string) ($old['full_name'] ?? $applicant['full_name']),
    'email' => (string) ($old['email'] ?? $applicant['email']),
    'phone' => (string) ($old['phone'] ?? $applicant['phone']),
    'address' => (string) ($old['address'] ?? $applicant['address']),
    'position_applied' => (string) ($old['position_applied'] ?? $applicant['position_applied']),
    'years_experience' => (string) ($old['years_experience'] ?? $applicant['years_experience']),
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Applicant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">Edit Applicant #<?= (int) $applicant['id'] ?></h1>
        <a href="index.php" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="update.php?id=<?= (int) $applicant['id'] ?>" method="post" enctype="multipart/form-data" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" value="<?= e($view['full_name']) ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['full_name'] ?? '')) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= e($view['email']) ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['email'] ?? '')) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" value="<?= e($view['phone']) ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['phone'] ?? '')) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Position Applied</label>
                        <input type="text" name="position_applied" class="form-control <?= isset($errors['position_applied']) ? 'is-invalid' : '' ?>" value="<?= e($view['position_applied']) ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['position_applied'] ?? '')) ?></div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>" value="<?= e($view['address']) ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['address'] ?? '')) ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Years of Experience</label>
                        <input type="number" name="years_experience" min="0" class="form-control <?= isset($errors['years_experience']) ? 'is-invalid' : '' ?>" value="<?= e($view['years_experience']) ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['years_experience'] ?? '')) ?></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label d-block">Current Image</label>
                        <img src="<?= e((string) $applicant['image_path']) ?>" alt="Applicant Image" class="rounded border" style="width:120px;height:120px;object-fit:cover;">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Replace Image (Optional)</label>
                        <input type="file" name="image" accept="image/*" class="form-control <?= isset($errors['image']) ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['image'] ?? '')) ?></div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Applicant</button>
                    <a href="index.php" class="btn btn-light border">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
