<?php

header("X-Frame-Options: SAMEORIGIN");
header("Content-Type: application/json");
//header("Content-Type: text/plain");
require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; global $songs;
$token = json_decode(file_exists("/opt/spotify/token.json") ? file_get_contents("/opt/spotify/token.json") : file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/includes/app.json"), true);

if (!isset($_GET["id"]) || !isset($songs[$_GET["id"]])) {
    die();
}

$song = $songs[$_GET["id"]];

$id = json_decode(file_get_contents("https://api.spotify.com/v1/search?q=" . rawurlencode("track:" . $song["title"] . " artist:" . $song["artist"]) . "&type=track", false, stream_context_create([
    "http" => [
        "method" => "GET",
        "header" => "Authorization: Bearer " . $token["access_token"] . "\r\n"
    ]
])), true)["tracks"]["items"][0]["id"];

$data = json_decode(file_get_contents("http://localhost:8000/public/?trackid=" . $id), true);

if (isset($data) && str_ends_with($data["syncType"], "_SYNCED")) {
    die(json_encode([
        "synced" => true,
        "payload" => $data["lines"]
    ], JSON_PRETTY_PRINT));
} elseif (isset($data) && str_starts_with($data["syncType"], "UNSYNCED")) {
    die(json_encode([
        "synced" => false,
        "payload" => implode("\n", array_map(function ($i) {
            return $i["words"];
        }, $data["lines"]))
    ], JSON_PRETTY_PRINT));
}

$probe = [];
exec("ffprobe -v quiet -print_format json -show_format $_SERVER[DOCUMENT_ROOT]/assets/content/$_GET[id].flac", $probe);
$metadata = json_decode(implode("\n", $probe), true);

if (isset($metadata["format"]["tags"]["UNSYNCEDLYRICS"])) {
    die(json_encode([
        "synced" => false,
        "payload" => trim($metadata["format"]["tags"]["UNSYNCEDLYRICS"])
    ], JSON_PRETTY_PRINT));
} elseif (isset($metadata["format"]["tags"]["unsyncedlyrics"])) {
    die(json_encode([
        "synced" => false,
        "payload" => trim($metadata["format"]["tags"]["unsyncedlyrics"])
    ], JSON_PRETTY_PRINT));
}

$genius = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/app.json"), true)["genius"];

$id = array_values(array_filter(json_decode(file_get_contents("https://api.genius.com/search?q=" . rawurlencode($song["title"] . " " . $song["artist"]), false, stream_context_create([
    "http" => [
        "method" => "GET",
        "header" => "Authorization: Bearer " . $genius . "\r\n"
    ]
])), true)["response"]["hits"], function ($i) {
    return !str_contains(strtolower($i["result"]["artist_names"]), "genius");
}))[0]["result"]["id"];

$data = [];
exec('bash -c "cd /opt/spotify/spotify-lyrics-api; python genius.py ' . $id . '"', $data);
$data = array_slice(array_map(function ($i) {
    if (str_ends_with($i, "1Embed")
     || str_ends_with($i, "2Embed")
     || str_ends_with($i, "3Embed")
     || str_ends_with($i, "4Embed")
     || str_ends_with($i, "5Embed")
     || str_ends_with($i, "6Embed")
     || str_ends_with($i, "7Embed")
     || str_ends_with($i, "8Embed")
     || str_ends_with($i, "9Embed")
     || str_ends_with($i, "0Embed")
    ) {
        return substr($i, 0, -6);
    } elseif (str_ends_with($i, "Embed")) {
        return substr($i, 0, -5);
    } else {
        return $i;
    }
}, $data), 1);

if (count($data) > 0) {
    die(json_encode([
        "synced" => false,
        "payload" => str_replace("You might also like", "", str_replace("You might also like\n", "", trim(implode("\n", $data))))
    ], JSON_PRETTY_PRINT));
}

die(json_encode([
    "synced" => false,
    "payload" => null
], JSON_PRETTY_PRINT));