<?php
$inFile = 'file.txt';
$outFile = 'output.txt';
require_once('config.php');
$authKey = $config['authKey'];
$baseUrl = 'https://api.deepl.com/v1/translate?';
/*
"EN"
"DE"
"FR"
"ES"
"IT"
"NL"
"PL"
 */
$sourceLang = 'DE';
$targetLang = 'ES';

$handle = fopen($inFile, "r");
if ($handle) {
  while (($line = fgets($handle)) !== false) {
    $lineArr = explode(';', $line);
    $text = urlencode(str_replace('"', '', $lineArr[2]));

    $json = file_get_contents($baseUrl . "text=$text&source_lang=$sourceLang&target_lang=$targetLang&auth_key=$authKey");
    $jsonDec = json_decode($json, TRUE);
    $translation = preg_replace( "/\r|\n/", "", $jsonDec['translations'][0]['text']);
    file_put_contents($outFile, $lineArr[0] . ';' . $lineArr[1] . ';"' . $translation . '"' . "\n", FILE_APPEND | LOCK_EX);
    echo $translation . "\n";
  }
}