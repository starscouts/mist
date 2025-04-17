<?php

header("X-Frame-Options: SAMEORIGIN");
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php";
global $songs; global $_PROFILE; global $favorites;

if (!isset($_GET["i"]) || !isset($songs[$_GET["i"]])) return;

if (in_array($_GET["i"], $favorites)) {
    $favorites = array_filter($favorites, function ($i) {
        return $i !== $_GET["i"];
    });
}

file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-favorites.json", json_encode($favorites));