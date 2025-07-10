<?php
if (isset($_GET['link'])) {
  $url = $_GET['link'];
  $basename = basename($url);
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header("Content-Disposition: attachment; filename=\"$basename\"");
  readfile($url);
  exit;
}
?>
