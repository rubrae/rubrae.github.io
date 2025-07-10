<?php
if (!isset($_GET['link'])) {
    http_response_code(400);
    exit("Paramètre 'link' manquant.");
}

$url = $_GET['link'];

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    exit("URL invalide.");
}

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', 
]);

$data = curl_exec($ch);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($data === false || $httpCode >= 400) {
    http_response_code(500);
    exit("Erreur lors du téléchargement distant.");
}

$filename = basename(parse_url($url, PHP_URL_PATH));

header('Content-Description: File Transfer');
header('Content-Type: ' . $contentType);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($data));
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Expires: 0');

echo $data;
exit;
