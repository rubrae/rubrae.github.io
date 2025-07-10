<?php
if (!isset($_GET['link'])) {
    http_response_code(400);
    die('Paramètre link manquant');
}

$url = trim($_GET['link']);

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    die('URL invalide');
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

$data = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

if (curl_errno($ch)) {
    curl_close($ch);
    http_response_code(500);
    die('Erreur de téléchargement');
}

curl_close($ch);

if ($httpCode !== 200 || !$data) {
    http_response_code(404);
    die('Fichier introuvable');
}

$filename = basename(parse_url($url, PHP_URL_PATH));
if (empty($filename)) {
    $filename = 'download';
}

ob_clean();
header('Content-Type: ' . ($contentType ?: 'application/octet-stream'));
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($data));

echo $data;
exit;
?>
