<?php

declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function getFlash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function validateApplicant(array $input, bool $isUpdate = false): array
{
    $fields = [
        'full_name' => trim((string) ($input['full_name'] ?? '')),
        'email' => trim((string) ($input['email'] ?? '')),
        'phone' => trim((string) ($input['phone'] ?? '')),
        'address' => trim((string) ($input['address'] ?? '')),
        'position_applied' => trim((string) ($input['position_applied'] ?? '')),
        'years_experience' => trim((string) ($input['years_experience'] ?? '')),
    ];

    $errors = [];

    foreach ($fields as $key => $value) {
        if ($value === '') {
            $errors[$key] = 'This field is required.';
        }
    }

    if ($fields['email'] !== '' && !filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please provide a valid email address.';
    }

    if ($fields['years_experience'] !== '') {
        if (!ctype_digit($fields['years_experience'])) {
            $errors['years_experience'] = 'Experience must be a whole number.';
        }
    }

    if (!$isUpdate) {
        if (!isset($_FILES['image']) || (int) $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors['image'] = 'Image is required.';
        }
    }

    return [$errors, $fields];
}

function handleImageUpload(array $file, ?string $existingPath = null): array
{
    if (!isset($file['error']) || (int) $file['error'] === UPLOAD_ERR_NO_FILE) {
        return [null, null];
    }

    if ((int) $file['error'] !== UPLOAD_ERR_OK) {
        return [null, 'Image upload failed.'];
    }

    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return [null, 'Invalid upload source.'];
    }

    if ((int) ($file['size'] ?? 0) > 2 * 1024 * 1024) {
        return [null, 'Image must be 2MB or smaller.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string) $finfo->file($file['tmp_name']);

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    if (!isset($allowed[$mime])) {
        return [null, 'Only JPG, PNG, GIF, or WEBP files are allowed.'];
    }

    $extension = $allowed[$mime];
    $newFilename = uniqid('img_', true) . '.' . $extension;
    $relativePath = 'uploads/' . $newFilename;
    $targetPath = __DIR__ . DIRECTORY_SEPARATOR . $relativePath;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return [null, 'Failed to save uploaded image.'];
    }

    if ($existingPath !== null && $existingPath !== '') {
        $existingFullPath = __DIR__ . DIRECTORY_SEPARATOR . $existingPath;
        if (is_file($existingFullPath)) {
            unlink($existingFullPath);
        }
    }

    return [$relativePath, null];
}

function deleteImageFile(?string $relativePath): void
{
    if ($relativePath === null || $relativePath === '') {
        return;
    }

    $fullPath = __DIR__ . DIRECTORY_SEPARATOR . $relativePath;
    if (is_file($fullPath)) {
        unlink($fullPath);
    }
}
