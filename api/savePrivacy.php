<?php

header("X-Frame-Options: SAMEORIGIN");
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php";
global $songs; global $_PROFILE; global $favorites; global $privacy;

foreach ($privacy as $item => $_) {
    if (isset($_GET[$item]) && ($_GET[$item] === "0" || $_GET[$item] === "1" || $_GET[$item] === "2")) {
        $privacy[$item] = (int)$_GET[$item];
    }
}

file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-privacy.json", json_encode($privacy));