<?php

declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Applicant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">Create Applicant Record</h1>
        <a href="index.php" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="store.php" method="post" enctype="multipart/form-data" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" value="<?= e((string) ($old['full_name'] ?? '')) ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['full_name'] ?? '')) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= e((string) ($old['email'] ?? '')) ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['email'] ?? '')) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" value="<?= e((string) ($old['phone'] ?? '')) ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['phone'] ?? '')) ?></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Position Applied</label>
                        <input type="text" name="position_applied" class="form-control <?= isset($errors['position_applied']) ? 'is-invalid' : '' ?>" value="<?= e((string) ($old['position_applied'] ?? '')) ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['position_applied'] ?? '')) ?></div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>" value="<?= e((string) ($old['address'] ?? '')) ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['address'] ?? '')) ?></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Years of Experience</label>
                        <input type="number" name="years_experience" min="0" class="form-control <?= isset($errors['years_experience']) ? 'is-invalid' : '' ?>" value="<?= e((string) ($old['years_experience'] ?? '')) ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['years_experience'] ?? '')) ?></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="image" accept="image/*" class="form-control <?= isset($errors['image']) ? 'is-invalid' : '' ?>">
                        <div class="invalid-feedback"><?= e((string) ($errors['image'] ?? '')) ?></div>
                        <small class="text-muted">Max size: 2MB. Allowed: JPG, PNG, GIF, WEBP.</small>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Applicant</button>
                    <a href="index.php" class="btn btn-light border">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
