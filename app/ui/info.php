<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; global $songs; global $favorites;

if (!isset($_GET["i"]) || !isset($songs[$_GET["i"]])) {
    die();
}

$song = $songs[$_GET["i"]];
$fileName = str_replace(["/", "<", ">", ":", "\"", "\\", "|", "?", "*"], "-", $song["artist"] . " - " . $song["title"]);

function getSize($bytes) {
    if ($bytes < 1024) {
        return $bytes;
    }

    if ($bytes < 1024**2) {
        return round($bytes / 1024, 1) . " KB";
    }

    if ($bytes < 1024**3) {
        return round($bytes / 1024**2, 1) . " MB";
    }

    return round($bytes / 1024**3, 1) . " GB";
}

function timeToDuration($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor($seconds / 60) - ($hours * 60);
    $seconds = floor($seconds) - ($hours * 3600) - ($minutes * 60);
    $parts = [];

    if ($hours > 0) $parts[] = $hours . " hour" . ($hours > 1 ? "s" : "");
    if ($minutes > 0) $parts[] = $minutes . " minute" . ($minutes > 1 ? "s" : "");
    if ($seconds > 0) $parts[] = $seconds . " second" . ($seconds > 1 ? "s" : "");

    return implode(", ", $parts);
}

function getBitRate($bits) {
    $bitsValue = $bits . " bps";

    if ($bits > 1000) {
        if ($bits > 1000000) {
            if ($bits > 1000000000) {
                $bitsValue = round($bits / 1000000000, 2) . " Gbps";
            } else {
                $bitsValue = round($bits / 1000000, 2) . " Mbps";
            }
        } else {
            $bitsValue = round($bits / 1000, 2) . " kbps";
        }
    }

    $bytesValue = ($bits / 8) . " B/s";

    if (($bits / 8) > 1000) {
        if (($bits / 8) > 1000000) {
            if (($bits / 8) > 1000000000) {
                $bytesValue = round(($bits / 8) / 1000000000, 2) . " GB/s";
            } else {
                $bytesValue = round(($bits / 8) / 1000000, 2) . " MB/s";
            }
        } else {
            $bytesValue = round(($bits / 8) / 1000, 2) . " kB/s";
        }
    }

    return $bitsValue . " (" . $bytesValue . ")";
}

function getChannelConfiguration($c) {
    if ($c === 1) return " (Mono)";
    if ($c === 2) return " (Stereo)";
    if ($c === 3) return " (Stereo+1)";
    if ($c === 4) return " (3.1)";
    if ($c === 5) return " (4.1 or 5.0 Surround)";
    if ($c === 6) return " (5.1 Surround)";
    if ($c === 7) return " (6.1 or 7.0 Surround)";
    if ($c === 8) return " (7.1 Surround)";
    if ($c === 9) return " (9.0 or 7.2 Spatial Audio)";
    if ($c === 10) return " (9.1 Spatial Audio)";
    if ($c >= 11) return " (Dolby Atmos)";
    return "";
}

?>
<!doctype html>
<html lang="en">
<head>
    <script src="/assets/js/common.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/localforage.min.js"></script>
    <script src="/assets/fuse.min.js"></script>
    <script src="/assets/js/shortcuts.js"></script>
    <link id="native-css" href="/assets/native.css" rel="stylesheet" disabled>
</head>
<body class="crossplatform" style="background-color: transparent !important;">
    <script>
        if (navigator.userAgent.includes("MistNative/darwin") || navigator.userAgent.includes("MistNative/win32")) {
            document.getElementById("native-css").disabled = false;
            document.body.classList.remove("crossplatform");
        }
    </script>
    <div style="padding: 1rem;">
        <div style="display: grid; grid-template-columns: 96px 1fr; grid-gap: 15px;">
            <img alt="" src="/assets/content/<?= $_GET["i"] ?>.jpg" style="aspect-ratio: 1; width: 96px;">
            <div style="display: flex; align-items: center; width: 100%;">
                <div>
                    <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis; max-width: 100%;"><b><?= $song["title"] ?></b></div>
                    <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis; max-width: 100%;"><?= $song["artist"] ?></div>
                    <div style="white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis; max-width: 100%;"><?= $song["album"] ?></div>
                </div>
            </div>
        </div>

        <hr>

        <table style="width: 100%;">
            <tr>
                <td style="width: calc(100% / 3); text-align: right; padding-right: 10px; opacity: .5;">Track</td>
                <td><?php if (isset($song["disc"])): ?>Disc <?= $song["disc"] ?>, track <?php endif; ?><?= $song["track"] > 0 ? $song["track"] : "-" ?></td>
            </tr>
            <tr>
                <td style="width: calc(100% / 3); text-align: right; padding-right: 10px; opacity: .5;">Duration</td>
                <td><?= timeToDuration($song["length"]) ?></td>
            </tr>
            <tr>
                <td style="width: calc(100% / 3); text-align: right; padding-right: 10px; opacity: .5;">Bit rate</td>
                <td><?= getBitRate($song["bitRate"]) ?></td>
            </tr>
            <tr>
                <td style="width: calc(100% / 3); text-align: right; padding-right: 10px; opacity: .5;">Sample rate</td>
                <td><?= $song["sampleRate"] ?> Hz</td>
            </tr>
            <tr>
                <td style="width: calc(100% / 3); text-align: right; padding-right: 10px; opacity: .5;">Bits per sample</td>
                <td><?= $song["bitDepth"] ?> bits</td>
            </tr>
            <tr>
                <td style="width: calc(100% / 3); text-align: right; padding-right: 10px; opacity: .5;">Channels</td>
                <td><?= $song["channels"] ?><?= getChannelConfiguration($song["channels"]) ?></td>
            </tr>
            <tr>
                <td style="width: calc(100% / 3); text-align: right; padding-right: 10px; opacity: .5;">Year</td>
                <td><?= $song["date"] ?></td>
            </tr>
            <tr>
                <td style="width: calc(100% / 3); text-align: right; padding-right: 10px; opacity: .5;">High-resolution</td>
                <td><?= $song["hiRes"] ? "Yes" : "No" ?></td>
            </tr>
            <tr>
                <td style="width: calc(100% / 3); text-align: right; padding-right: 10px; opacity: .5;">Mist Stella</td>
                <td>
                    <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["i"] . ".stella")): ?>
                        <?php

                        $handle = fopen($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["i"] . ".stella", "r");
                        fseek($handle, 8);
                        $contents = fread($handle, 512 - 8);
                        fclose($handle);
                        $metadata = json_decode(trim(zlib_decode($contents)), true);

                        ?>
                        Yes (<?php if (isset($metadata["id"])): ?>#<?= $metadata["id"] ?>, <?php endif; ?>Version <?= $metadata["version"] ?>)
                    <?php else: ?>
                        No
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td style="width: calc(100% / 3); text-align: right; padding-right: 10px; opacity: .5;">Copyright</td>
                <td><?= trim($song["copyright"] ?? "") !== "" ? $song["copyright"] : "-" ?></td>
            </tr>
            <tr>
                <td style="width: calc(100% / 3); text-align: right; padding-right: 10px; opacity: .5;">File size (lossless)</td>
                <td><?= getSize(filesize($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["i"] . ".flac")) ?></td>
            </tr>
            <tr>
                <td style="width: calc(100% / 3); text-align: right; padding-right: 10px; opacity: .5;">File size (AAC-LC)</td>
                <td><?= getSize(filesize($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["i"] . ".m4a")) ?></td>
            </tr>
            <tr>
                <td style="width: calc(100% / 3); text-align: right; padding-right: 10px; opacity: .5;">File size (Stella)</td>
                <td>
                    <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["i"] . ".stella")): ?>
                        <?= getSize(filesize($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["i"] . ".stella")) ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <hr><?php $id = $_GET["i"]; ?>
        <a class="btn btn-primary" onclick="<?= in_array($id, $favorites) ? "un" : "" ?>favoriteSong('<?= $id ?>');" id="btn-favorite-<?= $id ?>"><img id="btn-favorite-<?= $id ?>-icon" alt="" src="/assets/icons/favorite-<?= in_array($id, $favorites) ? "on" : "off" ?>.svg" style="pointer-events: none; filter: invert(1); width: 24px; height: 24px; margin-right: 5px;"><span id="btn-favorite-<?= $id ?>-text"><?= in_array($id, $favorites) ? "Remove from favorites" : "Add to favorites" ?></span></a>
        <a style="float: right;" class="btn btn-outline-secondary" onclick="window.parent._modal.hide();">Close</a>
    </div>

    <script>
        window.sizeInterval = setInterval(() => {
            if (document.body.clientHeight > 0) {
                clearInterval(sizeInterval);
                window.parent.document.getElementById("modal-frame").style.height = document.body.clientHeight + "px";
            }
        });

        async function favoriteSong(id) {
            document.getElementById("btn-favorite-" + id + "-icon").src = "/assets/icons/favorite-on.svg";
            document.getElementById("btn-favorite-" + id + "-text").innerText = "Remove from favorites";
            document.getElementById("btn-favorite-" + id).onclick = () => {
                unfavoriteSong(id);
            }
            await fetch("/api/addFavorite.php?i=" + id);

            window.parent.redownloadFavorites();
        }

        async function unfavoriteSong(id) {
            document.getElementById("btn-favorite-" + id + "-icon").src = "/assets/icons/favorite-off.svg";
            document.getElementById("btn-favorite-" + id + "-text").innerText = "Add to favorites";
            document.getElementById("btn-favorite-" + id).onclick = () => {
                favoriteSong(id);
            }
            await fetch("/api/removeFavorite.php?i=" + id);

            <?php if (isset($favoritesList)): ?>
            location.reload();
            <?php endif; ?>

            window.parent.redownloadFavorites();
        }
    </script>
</body>
</html>