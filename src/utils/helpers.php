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

    $new_name = bin2hex(random_bytes(16)) . '.' . $ext;
    $target_file = $target_dir . $new_name;

    if (!move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['error' => 'Failed to save the uploaded file.'];
    }

    return ['path' => $target_file];
}

function download_avatar_from_url(string $url): array
{
    $url = trim($url);
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return ['error' => 'Invalid avatar URL.'];
    }

    $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
    $host = strtolower((string) parse_url($url, PHP_URL_HOST));

    if (!in_array($scheme, ['http', 'https'], true)) {
        return ['error' => 'Avatar URL must use HTTP or HTTPS.'];
    }

    $ips = gethostbynamel($host);
    if ($ips === false || empty($ips)) {
        return ['error' => 'Could not resolve the hostname.'];
    }

    $resolvedIp = $ips[0];

    if (!filter_var($resolvedIp, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
        return ['error' => 'URL not allowed'];
    }

    $options = [
        'http' => [
            'method' => "GET",
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36\r\n" .
                        "Accept: image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8\r\n" .
                        "Accept-Language: en-US,en;q=0.9\r\n" .
                        "Connection: close\r\n",
            'timeout' => 15,
            'follow_location' => 0,
            'max_redirects' => 0,
            'ignore_errors' => true
        ]
    ];

    $ctx = stream_context_create($options);
    $content = @file_get_contents($url, false, $ctx);

    if ($content === false || $content === '') {
        return ['error' => 'Failed to download avatar from the provided URL.'];
    }

    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->buffer($content);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];

    if (!isset($allowed[$mime])) {
        return ['error' => 'The provided URL does not point to a valid image.'];
    }

    $ext = $allowed[$mime];
    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    
    $targetDir = __DIR__ . '/../publics/uploads/avatars/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (file_put_contents($targetDir . $filename, $content) === false) {
        return ['error' => 'Failed to save the downloaded avatar.'];
    }

    return ['path' => 'uploads/avatars/' . $filename];
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

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function verify_csrf_token(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        echo '<h1>403 — Invalid CSRF token</h1>';
        exit;
    }
}
?>
