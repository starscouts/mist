<?php header("X-Frame-Options: SAMEORIGIN"); require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/session.php";

if (!isset($_GET["q"]) || trim($_GET["q"]) === "" || trim(preg_replace("/ +/m", " ", preg_replace("/[^a-z\d ]/m", " ", strtolower($_GET["q"])))) === "") {
    header("Location: home.php");
    die();
}

?>
<!doctype html>
<html lang="en">
<head>
    <script>
        if (typeof window.parent.openModal === "undefined") {
            location.href = "/app/#/home";
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search results for "<?= strip_tags($_GET["q"]) ?>"</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/dark.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/localforage.min.js"></script>
    <script src="/assets/fuse.min.js"></script>
    <script src="/assets/js/shortcuts.js"></script>
    <link id="native-css" href="/assets/native.css" rel="stylesheet" disabled>
</head>
<body class="crossplatform has-navigation">
    <div id="ui-navigation" style="z-index: 999; background-color: rgba(255, 255, 255, .75); position: fixed; top: 0; left: 0; right: 0; height: 32px; backdrop-filter: blur(50px); -webkit-backdrop-filter: blur(50px);">
        <div style="display: grid; grid-template-columns: max-content 1fr max-content; height: 100%;" class="container">
            <div id="ui-back-button" onclick="history.back();" style="display: flex; align-items: center; justify-content: center; text-align: center;">
                <img src="/assets/icons/back.svg" alt="Back" class="icon">
            </div>
            <div style="display: flex; align-items: center; justify-content: center; text-align: center;"><b>Search results for "<?= strip_tags($_GET["q"]) ?>"</b></div>
        </div>
    </div>
    <script src="/assets/js/common.js"></script>
    <div class="container">
        <br>
        <?php

        global $albums; global $songs;

        function getMatches($item) {
            $n = 0;
            $query = preg_replace("/ +/m", " ", preg_replace("/[^a-z\d ]/m", " ", strtolower($_GET["q"])));

            if (isset($item["title"]) && str_contains(preg_replace("/ +/m", " ", preg_replace("/[^a-z\d ]/m", " ", strtolower($item["title"]))), $query)) $n++;
            if (isset($item["artist"]) && str_contains(preg_replace("/ +/m", " ", preg_replace("/[^a-z\d ]/m", " ", strtolower($item["artist"]))), $query)) $n++;
            if (isset($item["album"]) && str_contains(preg_replace("/ +/m", " ", preg_replace("/[^a-z\d ]/m", " ", strtolower($item["album"]))), $query)) $n++;

            return $n;
        }

        $albums = array_filter($albums, function ($i) {
            return getMatches($i) > 0;
        });

        uasort($albums, function ($a, $b) {
            return strcmp($a["title"], $b["title"]);
        });

        uasort($albums, function ($a, $b) {
            return strcmp($a["artist"], $b["artist"]);
        });

        uasort($albums, function ($a, $b) {
            return getMatches($b) - getMatches($a);
        });

        $songs = array_filter($songs, function ($i) {
            return getMatches($i) > 0;
        });

        uasort($songs, function ($a, $b) {
            return getMatches($b) - getMatches($a);
        });

        ?>

        <div id="album-grid" style="display: grid; grid-template-columns: repeat(5, 1fr);">
            <?php foreach ($albums as $id => $album): ?>
                <a id="album-<?= $id ?>" data-item-track="<?= $album["title"] ?>" data-item-artist="<?= $album["artist"] ?>" href="listing.php?a=<?= $id ?>" style="padding: 10px; color: inherit; text-decoration: inherit;" class="album">
                    <img class="album-list-art" alt="" src="/assets/content/<?= $id ?>.jpg" style="width: 100%; aspect-ratio: 1; border-radius: 5px; margin-bottom: 5px;">
                    <div class="album-list-item" style="max-width: calc(80vw / 5); white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $album["title"] ?></div>
                    <div class="album-list-item" style="max-width: calc(80vw / 5); opacity: .5; white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;"><?= $album["artist"] ?></div>
                </a>
            <?php endforeach; ?>
        </div>
        <?php global $songs; displayList($songs); ?>
    </div>

    <br><br>
</body>
</html>