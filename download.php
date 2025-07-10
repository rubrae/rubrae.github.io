<?php
if (isset($_GET['link'])) {
    $url = $_GET['link'];

    // Vérifie SSL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        http_response_code(400);
        exit("URL invalide.");
    }

    $filename = basename(parse_url($url, PHP_URL_PATH));

    // Récupère 
    $imageData = @file_get_contents($url);
    if ($imageData === false) {
        http_response_code(404);
        exit("Fichier introuvable.");
    }
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header('Content-Length: ' . strlen($imageData));
    echo $imageData;
    exit;
}
?>
