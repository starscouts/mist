<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php"; global $songs;

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

?>
<!doctype html>
<html lang="en">
<head>
    <script>
        if (typeof window.parent.parent.openModal === "undefined") {
            location.href = "/app/";
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>download</title>
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
    <script src="/assets/js/common.js"></script>
    <div style="padding: 1rem;">
        <p>
            <?= $song["artist"] ?> — <?= $song["title"] ?><br>
            <?= $song["album"] ?>
        </p>

        <p>Select the version of the song you would like to download:</p>
        <ul class="list-group">
            <li class="list-group-item">Lossless (FLAC, <?= getSize(filesize($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["i"] . ".flac")) ?>)<a style="float: right;" download="<?= $fileName ?>.flac" href="/assets/content/<?= $_GET["i"] ?>.flac">Download</a></li>
            <li class="list-group-item">AAC-LC (MP4, <?= getSize(filesize($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["i"] . ".m4a")) ?>)<a style="float: right;" download="<?= $fileName ?>.m4a" href="/assets/content/<?= $_GET["i"] ?>.m4a">Download</a></li>
            <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["i"] . ".stella")): ?>
            <li class="list-group-item">Mist Stella (<?= getSize(filesize($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["i"] . ".stella")) ?>)<a style="float: right;" download="<?= $fileName ?>.stella" href="/assets/content/<?= $_GET["i"] ?>.stella">Download</a></li>
            <?php else: ?>
            <li class="list-group-item">Mist Stella (not available)</li>
            <?php endif; ?>
            <li class="list-group-item">Album art (JPEG, <?= getSize(filesize($_SERVER['DOCUMENT_ROOT'] . "/assets/content/" . $_GET["i"] . ".jpg")) ?>)<a style="float: right;" download="<?= $fileName ?>.jpg" href="/assets/content/<?= $_GET["i"] ?>.jpg">Download</a></li>
        </ul>
    </div>

    <script>
        window.sizeInterval = setInterval(() => {
            if (document.body.clientHeight > 0) {
                clearInterval(sizeInterval);
                window.parent.document.getElementById("modal-frame").style.height = document.body.clientHeight + "px";
            }
        });
    </script>
</body>
</html>