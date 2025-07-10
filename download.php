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

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'pdf'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExtensions)) {
    http_response_code(400);
    exit("Erreur : type de fichier non autorisé.");
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); /
$data = curl_exec($ch);

if (curl_errno($ch)) {
    http_response_code(500);
    exit("Erreur cURL : " . curl_error($ch));
}

$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

if (!$data) {
    http_response_code(404);
    exit("Erreur : impossible de récupérer le fichier.");
}

header('Content-Description: File Transfer');
header('Content-Type: ' . $contentType);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($data));

echo $data;
exit;
