<?php

declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

$pdo = getPdo();
initializeDatabase($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id <= 0) {
    setFlash('danger', 'Invalid record ID.');
    redirect('index.php');
}

$stmt = $pdo->prepare('SELECT image_path FROM applicants WHERE id = :id');
$stmt->execute([':id' => $id]);
$row = $stmt->fetch();

if (!$row) {
    setFlash('danger', 'Record not found.');
    redirect('index.php');
}

$delete = $pdo->prepare('DELETE FROM applicants WHERE id = :id');
$delete->execute([':id' => $id]);

deleteImageFile((string) $row['image_path']);

setFlash('success', 'Applicant deleted successfully.');
redirect('index.php');
