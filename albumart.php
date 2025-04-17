<?php

$songs = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/assets/content/songs.json"), true);
$albums = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/assets/content/albums.json"), true);

if (!isset($_GET["i"]) || (!isset($songs[$_GET["i"]]) && !isset($albums[$_GET["i"]]))) {
    die();
}

header("Cache-Control: public, max-age=604800, immutable");
header("Content-Type: image/jpeg");
header("Content-Length: " . filesize($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["i"] . ".jpg"));
readfile($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["i"] . ".jpg");