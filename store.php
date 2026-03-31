<?php

declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

$pdo = getPdo();
initializeDatabase($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

[$errors, $fields] = validateApplicant($_POST, false);

if ($errors) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = $fields;
    redirect('create.php');
}

[$imagePath, $imageError] = handleImageUpload($_FILES['image']);
if ($imageError !== null || $imagePath === null) {
    $_SESSION['errors'] = ['image' => $imageError ?? 'Image is required.'];
    $_SESSION['old'] = $fields;
    redirect('create.php');
}

$sql = 'INSERT INTO applicants (full_name, email, phone, address, position_applied, years_experience, image_path)
        VALUES (:full_name, :email, :phone, :address, :position_applied, :years_experience, :image_path)';

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':full_name' => $fields['full_name'],
    ':email' => $fields['email'],
    ':phone' => $fields['phone'],
    ':address' => $fields['address'],
    ':position_applied' => $fields['position_applied'],
    ':years_experience' => (int) $fields['years_experience'],
    ':image_path' => $imagePath,
]);

setFlash('success', 'Applicant created successfully.');
redirect('index.php');
