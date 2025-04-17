<?php

header("X-Frame-Options: SAMEORIGIN");
header("Content-Type: application/json");
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; global $songs;

if (!isset($_GET["id"]) || !isset($songs[$_GET["id"]])) {
    die();
}

die(file_exists($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["id"] . ".stella") ? "true" : "false");