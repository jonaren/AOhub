<?php
// Local dev router — processes .html and .php files through PHP
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . $uri;

// Serve directory index
if (is_dir($file)) {
    foreach (['index.php', 'index.html'] as $index) {
        if (file_exists($file . '/' . $index)) {
            $file = $file . '/' . $index;
            break;
        }
    }
}

// Pass static assets (css, js, images, fonts, etc.) directly to built-in server
$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$static = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot', 'pdf', 'webmanifest', 'txt', 'json'];
if (in_array($ext, $static) && file_exists($file)) {
    return false;
}

// Process PHP/HTML through PHP interpreter
if (file_exists($file) && in_array($ext, ['php', 'html'])) {
    include $file;
    return;
}

// 404 fallback
http_response_code(404);
echo "<h1>404 Not Found</h1><p>$uri</p>";
