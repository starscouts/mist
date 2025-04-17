<?php

// ffmpeg -y -i <source> -vf scale=1280:720,fps=fps=24 -an <id>.webm

header("X-Frame-Options: DENY");
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php";
header("Location: /app/");
die();