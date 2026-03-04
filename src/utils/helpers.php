<?php

function handle_file_upload(?array $file, string $target_dir, array $allowed_exts, ?array $allowed_mimes = null): array
{
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return ['error' => 'No valid file uploaded.'];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_exts)) {
        return ['error' => 'Invalid file type. Allowed: ' . implode(', ', $allowed_exts) . '.'];
    }

    if ($allowed_mimes !== null) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        if (!in_array($mime, $allowed_mimes)) {
            return ['error' => 'Invalid MIME type.'];
        }
    }

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $new_name    = bin2hex(random_bytes(16)) . '.' . $ext;
    $target_file = $target_dir . $new_name;

    if (!move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['error' => 'Failed to save the uploaded file.'];
    }

    return ['path' => $target_file];
}

function require_fields(array $fields, array $source): ?string
{
    foreach ($fields as $f) {
        if (!isset($source[$f]) || trim($source[$f]) === '') {
            return "Missing required field: $f";
        }
    }
    return null;
}
