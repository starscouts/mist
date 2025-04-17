<?php

header("X-Frame-Options: SAMEORIGIN");
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php";
global $songs; global $favorites; global $_PROFILE;

$hasAlbum = false;
$favoritesList = true;
$list = [];

if (!isset($_GET["u"])) {
    header("Location: favorites.php?u=" . $_PROFILE["id"]);
    die();
}

$correctFavorites = $favorites;

if (preg_match("/[^a-f0-9-]/m", $_GET["u"]) == 0 && $_GET["u"] !== $_PROFILE["id"]) {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_GET["u"] . "-privacy.json")) {
        $userPrivacy = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_GET["u"] . "-privacy.json"), true);
        if ($userPrivacy["listen"] >= 1) {
            $userId = $_GET["u"];
            $correctFavorites = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_GET["u"] . "-favorites.json"), true);
        } else {
            header("Location: favorites.php?u=" . $_PROFILE["id"]);
            die();
        }
    } else {
        header("Location: favorites.php?u=" . $_PROFILE["id"]);
        die();
    }
} else {
    $userId = $_PROFILE["id"];
}

foreach ($correctFavorites as $id) {
    if (isset($songs[$id])) $list[$id] = $songs[$id];
}

unset($_GET["a"]);
require_once "listing.php";