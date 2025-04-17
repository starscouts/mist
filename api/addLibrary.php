<?php

header("X-Frame-Options: SAMEORIGIN");
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php";
global $albums; global $_PROFILE; global $library;

if (!isset($_GET["i"]) || !isset($albums[$_GET["i"]])) return;

if (!in_array($_GET["i"], $library)) {
    $library[] = $_GET["i"];
}

file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-library.json", json_encode($library));