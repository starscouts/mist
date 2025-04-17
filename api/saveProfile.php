<?php

header("X-Frame-Options: SAMEORIGIN");
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; global $profile; global $_PROFILE;

if (isset($_POST["nsfw"])) {
    if ($_POST["nsfw"] === "false") {
        $profile["nsfw"] = false;
    } else {
        $profile["nsfw"] = true;
    }
}

if (isset($_POST["description"])) {
    $profile["description"] = substr(strip_tags($_POST["description"]), 0, 500);
}

if (isset($_POST["url"])) {
    $derpibooruID = preg_replace("/^http(s|):\/\/(www\.|)derpibooru\.org\/images\/(\d+).*$/m", "$3", $_POST["url"]);

    if ($derpibooruID === $_POST["url"]) {
        $profile["banner"] = $profile["banner_orig"] = substr(strip_tags($_POST["url"]), 0, 120);
    } else {
        $data = json_decode(file_get_contents("https://derpibooru.org/api/v1/json/images/" . $derpibooruID, false, stream_context_create([
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: Mozilla/5.0 (+MistProfile/1.0; raindrops@equestria.dev)\r\n"
            ]
        ])), true);

        if (isset($data) && json_last_error() === JSON_ERROR_NONE && isset($data["image"]) && isset($data["image"]["view_url"])) {
            $profile["banner"] = $data["image"]["view_url"];
            $profile["banner_orig"] = substr(strip_tags($_POST["url"]), 0, 120);
        } else {
            $profile["banner"] = $profile["banner_orig"] = substr(strip_tags($_POST["url"]), 0, 120);
        }
    }
}

file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/users/" . $_PROFILE["id"] . "-profileSettings.json", json_encode($profile));