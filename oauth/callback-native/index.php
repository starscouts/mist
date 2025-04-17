<?php

var_dump("START");
header("X-Frame-Options: DENY");
$app = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/app.json"), true);
$server = "account.equestria.dev";

header("Content-Type: text/plain");

if (!isset($_GET['code'])) {
    die();
}

$crl = curl_init('https://' . $server . '/hub/api/rest/oauth2/token');
curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($crl, CURLINFO_HEADER_OUT, true);
curl_setopt($crl, CURLOPT_POST, true);
curl_setopt($crl, CURLOPT_HTTPHEADER, [
    "Authorization: Basic " . base64_encode($app["id"] . ":" . $app["secret"]),
    "Content-Type: application/x-www-form-urlencoded",
    "Accept: application/json"
]);
curl_setopt($crl, CURLOPT_POSTFIELDS, "grant_type=authorization_code&redirect_uri=" . urlencode(($_SERVER['SERVER_PORT'] === "8043" ? "https://mist-testing.equestria.horse" : "https://mist.equestria.horse") . "/oauth/callback-native") . "&code=" . $_GET['code']);

$result = curl_exec($crl);
$result = json_decode($result, true);

curl_close($crl);

if (isset($result["access_token"])) {
    $crl = curl_init('https://' . $server . '/hub/api/rest/users/me');
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($crl, CURLINFO_HEADER_OUT, true);
    curl_setopt($crl, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . $result["access_token"],
        "Accept: application/json"
    ]);

    $result = $result_orig = curl_exec($crl);
    $result = json_decode($result, true);

    if (!in_array($result["id"], $app["allowed"])) {
        header("HTTP/1.1 403 Forbidden");
        die("Not allowed to log in to this application. This will be reported.");
    }

    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/includes/tokens")) mkdir($_SERVER['DOCUMENT_ROOT'] . "/includes/tokens");

    $token = "wv_" . bin2hex(random_bytes(64));

    file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/tokens/" . $token, $result_orig);
    header("Location: http://127.0.0.1:12981/?token=$token");
    die();
}
