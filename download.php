<?php
echo "Script PHP fonctionne !<br>";
echo "Paramètres GET reçus :<br>";
var_dump($_GET);

if (isset($_GET['link'])) {
    echo "<br>URL à télécharger : " . $_GET['link'] . "<br>";
    
    $url = urldecode($_GET['link']);
    echo "URL décodée : " . $url . "<br>";
    
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        echo "URL valide ✓<br>";
    } else {
        echo "URL invalide ✗<br>";
    }
    
    if (function_exists('curl_init')) {
        echo "cURL disponible ✓<br>";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        
        echo "Code HTTP : " . $httpCode . "<br>";
        echo "Type de contenu : " . $contentType . "<br>";
        
        if (curl_errno($ch)) {
            echo "Erreur cURL : " . curl_error($ch) . "<br>";
        }
        
        curl_close($ch);
    } else {
        echo "cURL non disponible ✗<br>";
    }
}
?>

<form method="GET">
    <label>Testez avec une URL :</label><br>
    <input type="text" name="link" value="https://httpbin.org/image/png" style="width: 400px;"><br>
    <input type="submit" value="Tester">
</form>
