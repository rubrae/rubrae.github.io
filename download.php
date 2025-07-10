<?php
if (!isset($_GET['link']) || empty($_GET['link'])) {
    http_response_code(400);
    exit("Erreur : paramètre 'link' manquant.");
}

$url = urldecode($_GET['link']);

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    exit("Erreur : URL invalide.");
}

$filename = basename(parse_url($url, PHP_URL_PATH));

if (empty($filename) || $filename === '/') {
    $filename = 'download_' . date('Y-m-d_H-i-s');
}

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'pdf', 'txt', 'doc', 'docx', 'zip'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

if (!empty($ext) && !in_array($ext, $allowedExtensions)) {
    http_response_code(400);
    exit("Erreur : type de fichier non autorisé.");
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

$data = curl_exec($ch);

if (curl_errno($ch)) {
    $error = curl_error($ch);
    curl_close($ch);
    http_response_code(500);
    exit("Erreur cURL : " . $error);
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$contentLength = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

curl_close($ch);

if ($httpCode >= 400) {
    http_response_code($httpCode);
    exit("Erreur HTTP : " . $httpCode);
}


if (!$data || strlen($data) == 0) {
    http_response_code(404);
    exit("Erreur : impossible de récupérer le fichier ou fichier vide.");
}


if (ob_get_level()) {
    ob_end_clean();
}


header('Content-Description: File Transfer');
header('Content-Type: ' . ($contentType ?: 'application/octet-stream'));
header('Content-Disposition: attachment; filename="' . addslashes($filename) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . strlen($data));


echo $data;

?>
