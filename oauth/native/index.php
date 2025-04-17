<?php

header("X-Frame-Options: DENY");
$app = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/app.json"), true);
$server = "account.equestria.dev";

header("Location: https://$server/hub/api/rest/oauth2/auth?client_id=" . $app["id"] . "&response_type=code&redirect_uri=" . ($_SERVER['SERVER_PORT'] === "8043" ? "https://mist-testing.equestria.horse" : "https://mist.equestria.horse") . "/oauth/callback-native&scope=Hub&request_credentials=required&access_type=offline");
die();
