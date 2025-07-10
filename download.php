<?php
if (!isset($_GET['link'])) {
    http_response_code(400);
    exit("Lien manquant.");
}

$url = urldecode($_GET['link']);
$filename = basename(parse_url($url, PHP_URL_PATH));

if (!preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $filename)) {
    http_response_code(400);
    exit("Extension non autorisée.");
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$data = curl_exec($ch);

if (!$data) {
    http_response_code(500);
    exit("Échec du téléchargement.");
}

$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

header("Content-Type: $contentType");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Length: " . strlen($data));
echo $data;
exit;
