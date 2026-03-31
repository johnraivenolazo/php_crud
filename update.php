<?php

declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

$pdo = getPdo();
initializeDatabase($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

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

[$errors, $fields] = validateApplicant($_POST, true);
if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $fields;
    redirect('edit.php?id=' . $id);
}

$imagePath = (string) $applicant['image_path'];
if (isset($_FILES['image']) && (int) $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    [$newImagePath, $imageError] = handleImageUpload($_FILES['image'], $imagePath);
    if ($imageError !== null || $newImagePath === null) {
        $_SESSION['errors'] = ['image' => $imageError ?? 'Failed to upload image.'];
        $_SESSION['old'] = $fields;
        redirect('edit.php?id=' . $id);
    }

    $imagePath = $newImagePath;
}

$sql = 'UPDATE applicants
        SET full_name = :full_name,
            email = :email,
            phone = :phone,
            address = :address,
            position_applied = :position_applied,
            years_experience = :years_experience,
            image_path = :image_path
        WHERE id = :id';

$update = $pdo->prepare($sql);
$update->execute([
    ':full_name' => $fields['full_name'],
    ':email' => $fields['email'],
    ':phone' => $fields['phone'],
    ':address' => $fields['address'],
    ':position_applied' => $fields['position_applied'],
    ':years_experience' => (int) $fields['years_experience'],
    ':image_path' => $imagePath,
    ':id' => $id,
]);

setFlash('success', 'Applicant updated successfully.');
redirect('index.php');
