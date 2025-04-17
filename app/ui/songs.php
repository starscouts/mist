<?php

header("X-Frame-Options: SAMEORIGIN");
unset($_GET["a"]);
require_once "./listing.php";