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
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
$data = curl_exec($ch);

if ($data === false) {
    http_response_code(500);
    exit("Erreur lors du téléchargement.");
}

$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$filename = basename(parse_url($url, PHP_URL_PATH));
curl_close($ch);

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
