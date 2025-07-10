<?php
if (!isset($_GET['link'])) {
    http_response_code(400);
    exit("Lien manquant.");
}

$url = $_GET['link'];

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    exit("Lien invalide.");
}

$filename = basename(parse_url($url, PHP_URL_PATH));

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$data = curl_exec($ch);
$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
curl_close($ch);

if ($data === false) {
    http_response_code(500);
    exit("Erreur lors du téléchargement.");
}

header('Content-Description: File Transfer');
header('Content-Type: ' . $contentType);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($data));
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Expires: 0');

echo $data;
exit;
?>
