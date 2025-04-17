<?php

header("X-Frame-Options: SAMEORIGIN");
unset($_GET["a"]);
$onlyStella = 1;
global $onlyStella;
require_once "./listing.php";