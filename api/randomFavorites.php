<?php

header("X-Frame-Options: SAMEORIGIN");
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php";
header("Content-Type: application/json");
global $favorites;
shuffle($favorites);
die(json_encode($favorites));