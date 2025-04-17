<?php

header("X-Frame-Options: SAMEORIGIN");
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php";
global $songs; global $_PROFILE; global $history;

if (!isset($_GET["i"]) || !isset($songs[$_GET["i"]])) return;
$history[] = [
    "item" => $_GET["i"],
    "date" => date('c')
];

file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-history.json", json_encode($history));